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

    public function index(int $id): void
    {
        Auth::check();

        $eventId = $this->reviewRepo->getEventIdByArticleId($id);

        if (!$eventId) {
            echo "Événement introuvable";
            return;
        }

        $reviews = $this->reviewRepo->getByEvent($eventId);
        $avgRate = $this->reviewRepo->getAverageRating($eventId);

        $canReview = $this->partRepo->hasParticipated(Auth::id(), $eventId);

        $this->renderWithLayout('reviews/index', [
            'reviews'   => $reviews,
            'average'   => $avgRate,
            'canReview' => $canReview,
            'articleId' => $id
        ]);
    }

    public function store(int $id): void
    {
        Auth::check();

        $rating  = intval($_POST['rating'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');

        $eventId = $this->reviewRepo->getEventIdByArticleId($id);

        if (!$this->partRepo->hasParticipated(Auth::id(), $eventId)) {
            $_SESSION['error'] = "Vous devez participer pour laisser un avis.";
            $this->redirect("articles/{$id}/reviews");
        }

        $this->reviewRepo->create([
            'rating'     => $rating,
            'comment'    => $comment,
            'student_id' => Auth::id(),
            'event_id'   => $eventId
        ]);

        $this->redirect("articles/{$id}/reviews");
    }

    public function delete(int $id): void
    {
        Auth::check();

        if ($_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/');
        }

        $this->reviewRepo->delete($id);

        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }
}
