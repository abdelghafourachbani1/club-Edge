<?php

namespace App\Controllers;

class ReviewController extends \Controller {

    public function index(int $id): void {
        echo "<h1>Reviews for Event #{$id}</h1>";
        echo "<p>Cette page sera développée par la Personne 5 (Articles & Avis)</p>";
        echo "<a href='/club-Edge/events/{$id}'>← Retour à l'événement</a>";
    }

    public function store(int $id): void {
        $_SESSION['success'] = 'Review feature coming soon!';
        $this->redirect('/events/' . $id);
    }
}