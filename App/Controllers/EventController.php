<?php

namespace App\Controllers;

use App\Repositories\EventRepository;
use App\Repositories\ParticipationRepository;


class EventController extends \Controller {
    private EventRepository $eventRepo;
    private ParticipationRepository $participationRepo;

    public function __construct() {
        parent::__construct();
        $this->eventRepo = new EventRepository();
        $this->participationRepo = new ParticipationRepository();
    }

    public function index(): void {
        $events = $this->eventRepo->findAll();
        
        foreach ($events as $event) {
            $event->participantCount = $this->participationRepo->countParticipants($event->getId());
        }

        $this->renderWithLayout('events/index', [
            'events' => $events,
            'title' => 'All Events'
        ]);
    }

    public function byClub(int $clubId): void {
        $events = $this->eventRepo->findByClubId($clubId);
        
        foreach ($events as $event) {
            $event->participantCount = $this->participationRepo->countParticipants($event->getId());
        }

        $this->renderWithLayout('events/index', [
            'events' => $events,
            'clubId' => $clubId,
            'title' => 'Club Events'
        ]);
    }

    public function upcoming(): void {
        $events = $this->eventRepo->findUpcoming();
        
        foreach ($events as $event) {
            $event->participantCount = $this->participationRepo->countParticipants($event->getId());
        }

        $this->renderWithLayout('events/index', [
            'events' => $events,
            'title' => 'Upcoming Events'
        ]);
    }

    public function past(): void {
        $events = $this->eventRepo->findPast();
        
        foreach ($events as $event) {
            $event->participantCount = $this->participationRepo->countParticipants($event->getId());
        }

        $this->renderWithLayout('events/index', [
            'events' => $events,
            'title' => 'Past Events'
        ]);
    }

public function show(int $id): void {
    $event = $this->eventRepo->findById($id);

    if (!$event) {
        $this->redirect('/events');
        return;
    }

    $participants = $this->participationRepo->getParticipants($id);
    $participantCount = count($participants);

    $isRegistered = false;
    if (isset($_SESSION['user_id'])) {
        $isRegistered = $this->participationRepo->isRegistered($_SESSION['user_id'], $id);
    }

    $reviewRepo = new \App\Repositories\ReviewRepository();
    $reviews = $reviewRepo->findByEventId($id);
    $averageRating = $reviewRepo->getAverageRating($id);

    $articleRepo = new \App\Repositories\ArticleRepository();
    $articles = $articleRepo->findByEventId($id);

    $this->renderWithLayout('events/show', [
        'event' => $event,
        'participants' => $participants,
        'participantCount' => $participantCount,
        'isRegistered' => $isRegistered,
        'reviews' => $reviews,              
        'averageRating' => $averageRating,  
        'articles' => $articles,            
        'title' => $event->getTitle()
    ]);
}

    public function create(): void {
        // TODO: Add authentication check - president only
        if (!isset($_SESSION['user_id']) || !$this->isPresident($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        $this->renderWithLayout('events/create', [
            'title' => 'Create New Event'
        ]);
    }

    public function store(): void {
        $errors = [];
        
        if (empty($_POST['title'])) {
            $errors[] = 'Title is required';
        }
        if (empty($_POST['description'])) {
            $errors[] = 'Description is required';
        }
        if (empty($_POST['event_date'])) {
            $errors[] = 'Event date is required';
        }
        if (empty($_POST['location'])) {
            $errors[] = 'Location is required';
        }
        if (empty($_POST['club_id'])) {
            $errors[] = 'Club ID is required';
        }

        if (!empty($_POST['event_date'])) {
            $eventDate = new \DateTime($_POST['event_date']);
            if ($eventDate < new \DateTime()) {
                $errors[] = 'Event date must be in the future';
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect('/events/create');
            return;
        }

        $images = [];
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $images = $this->handleImageUpload($_FILES['images']);
        }

        $event = $this->eventRepo->create([
            'title' => htmlspecialchars($_POST['title']),
            'description' => htmlspecialchars($_POST['description']),
            'event_date' => $_POST['event_date'],
            'location' => htmlspecialchars($_POST['location']),
            'club_id' => (int) $_POST['club_id'],
            'images' => $images,
            'status' => 'active'
        ]);

        if ($event) {
            $_SESSION['success'] = 'Event created successfully!';
            $this->redirect('/events/' . $event->getId());
        } else {
            $_SESSION['errors'] = ['Failed to create event'];
            $this->redirect('/events/create');
        }
    }

    public function edit(int $id): void {
        $event = $this->eventRepo->findById($id);

        if (!$event) {
            $this->redirect('/events');
            return;
        }

        // TODO: Check if user is president of the club
        if (!$this->eventRepo->isPresidentOfEventClub($id, $_SESSION['user_id'])) {
            $this->redirect('/events');
            return;
        }

        $this->renderWithLayout('events/edit', [
            'event' => $event,
            'title' => 'Edit Event'
        ]);
    }

    public function update(int $id): void {
        // TODO: Add authentication and authorization checks

        $event = $this->eventRepo->findById($id);
        if (!$event) {
            $this->redirect('/events');
            return;
        }

        $data = [];
        
        if (!empty($_POST['title'])) {
            $data['title'] = htmlspecialchars($_POST['title']);
        }
        if (!empty($_POST['description'])) {
            $data['description'] = htmlspecialchars($_POST['description']);
        }
        if (!empty($_POST['event_date'])) {
            $data['event_date'] = $_POST['event_date'];
        }
        if (!empty($_POST['location'])) {
            $data['location'] = htmlspecialchars($_POST['location']);
        }
        if (isset($_POST['status'])) {
            $data['status'] = $_POST['status'];
        }

        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $newImages = $this->handleImageUpload($_FILES['images']);
            $existingImages = $event->getImages();
            $data['images'] = array_merge($existingImages, $newImages);
        }

        if ($this->eventRepo->update($id, $data)) {
            $_SESSION['success'] = 'Event updated successfully!';
        } else {
            $_SESSION['errors'] = ['Failed to update event'];
        }

        $this->redirect('/events/' . $id);
    }

    public function delete(int $id): void {
        // TODO: Add authentication and authorization checks

        if ($this->eventRepo->delete($id)) {
            $_SESSION['success'] = 'Event deleted successfully!';
        } else {
            $_SESSION['errors'] = ['Failed to delete event'];
        }

        $this->redirect('/events');
    }

    public function register(int $id): void {
        // TODO: Check authentication
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        $event = $this->eventRepo->findById($id);
        if (!$event) {
            $this->redirect('/events');
            return;
        }

        if ($event->isPast()) {
            $_SESSION['errors'] = ['Cannot register for past events'];
            $this->redirect('/events/' . $id);
            return;
        }

        if ($this->participationRepo->register($_SESSION['user_id'], $id)) {
            $_SESSION['success'] = 'Successfully registered for event!';
        } else {
            $_SESSION['errors'] = ['Failed to register for event'];
        }

        $this->redirect('/events/' . $id);
    }

    public function unregister(int $id): void {
        // TODO: Check authentication
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        if ($this->participationRepo->unregister($_SESSION['user_id'], $id)) {
            $_SESSION['success'] = 'Successfully unregistered from event!';
        } else {
            $_SESSION['errors'] = ['Failed to unregister from event'];
        }

        $this->redirect('/events/' . $id);
    }

    public function participants(int $id): void {
        $event = $this->eventRepo->findById($id);
        if (!$event) {
            $this->redirect('/events');
            return;
        }

        // TODO: Check if user is president
        
        $participants = $this->participationRepo->getParticipants($id);

        $this->renderWithLayout('events/participants', [
            'event' => $event,
            'participants' => $participants,
            'title' => 'Event Participants'
        ]);
    }

    private function handleImageUpload(array $files): array {
        $uploadedPaths = [];
        $uploadDir = BASE_PATH . '/public/uploads/events/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileCount = count($files['name']);
        
        for ($i = 0; $i < $fileCount; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $tmpName = $files['tmp_name'][$i];
                $originalName = basename($files['name'][$i]);
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                
                $filename = uniqid('event_') . '_' . time() . '.' . $extension;
                $destination = $uploadDir . $filename;

                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (!in_array(strtolower($extension), $allowedTypes)) {
                    continue;
                }

                if ($files['size'][$i] > 5 * 1024 * 1024) {
                    continue;
                }

                if (move_uploaded_file($tmpName, $destination)) {
                    $uploadedPaths[] = '/uploads/events/' . $filename;
                }
            }
        }

        return $uploadedPaths;
    }
}