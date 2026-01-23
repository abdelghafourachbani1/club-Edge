<?php

namespace App\Controllers;

use Controller;
use App\Repositories\ReviewRepository;
use App\Repositories\ParticipationRepository;
use Auth;

class ReviewController extends Controller
{
    private ReviewRepository $reviewRepo;
    private ParticipationRepository $partRepo;

    public function __construct()
    {
        parent::__construct();
        $this->reviewRepo = new ReviewRepository();
        $this->partRepo   = new ParticipationRepository();
    }

    // Show reviews for an article's event
    public function index(int $id): void
    {
        Auth::check();

        // Find event_id via article
        $eventId = $this->reviewRepo->getEventIdByArticleId($id);

        if (!$eventId) {
            echo "Event not found!";
            return;
        }

        // Get all reviews
        $reviews = $this->reviewRepo->getByEvent($eventId);

        // Compute average
        $avg = $this->reviewRepo->getAverageRating($eventId);

        // Check if current user can post review
        $canReview = $this->partRepo->hasParticipated(Auth::id(), $eventId);

        $this->renderWithLayout('reviews/index', [
            'reviews'   => $reviews,
            'average'   => $avg,
            'canReview' => $canReview,
            'articleId' => $id
        ]);
    }

    // Store new review
    public function store(int $id): void
    {
        Auth::check();

        $rating  = intval($_POST['rating'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');

        $eventId = $this->reviewRepo->getEventIdByArticleId($id);
        $student = Auth::id();

        if (!$this->partRepo->hasParticipated($student, $eventId)) {
            $_SESSION['error'] = "Vous devez participer à l'évènement pour laisser un avis.";
            $this->redirect("/articles/{$id}/reviews");
        }

        $this->reviewRepo->create([
            'rating'     => $rating,
            'comment'    => $comment,
            'student_id' => $student,
            'event_id'   => $eventId
        ]);

        $this->redirect("/articles/{$id}/reviews");
    }

    // Delete review (admin only)
    public function delete(int $id): void
    {
        Auth::check();

        if ($_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/');
        }

        $this->reviewRepo->delete($id);

        // Redirect back
        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }
}
