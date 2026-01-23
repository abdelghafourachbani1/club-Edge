<?php
namespace App\Controllers;

// use App\Models\Database;
use App\Repositories\StudentRepository;
// use App\Repositories\UserRepository;
use Controller;

class StudentController extends Controller
{
    private $pdo;
    private $studentRepo;
    public function __construct()
    {
        $this->pdo = Database::getInstance();
        $this->studentRepo = new StudentRepository($this->pdo);
    }
    public function dashboard(): void {
        // $this->renderWithLayout('student/dashboard', ['user' => $_SESSION['user']]);
        $this->renderWithLayout('student/dashboard');
    }

    public function profile(): void {
        $this->renderWithLayout('student/profile', ['user' => $_SESSION['user']]);
    }

    public function updateProfile(): void {
        $this->renderWithLayout('student/update-profile', ['user' => $_SESSION['user']]);
    }

    public function updatePassword(): void {
        $this->renderWithLayout('student/update-password', ['user' => $_SESSION['user']]);
    }

    public function inscireEvent(): void {
        $this->studentRepo->inscireEvent($_SESSION['user']['id'], $_GET['event_id']);
        $this->renderWithLayout('student/dashboard');
    }

    public function desinscireEvent(): void {
        $this->studentRepo->desinscireEvent($_SESSION['user']['id'], $_GET['event_id']);
        $this->renderWithLayout('student/dashboard');
    }

    // // Inscription / Désinscription à un club (Règle : 1 étudiant = 1 club) max 8 membre en club
    public function sincrireDunClub ($clubId) {
        $isParticipatedInClub = $this->studentRepo->isParticipatedInClub($_SESSION['user']['id']);
        if ($isParticipatedInClub) {
            $error = "peut être inscrit dans un seul club";
            $this->renderWithLayout('student/dashboard', 'error', $error);
            return;
        }
        $nomreClub = $this->studentRepo->nombreClub($clubId);
        if ($nomreClub == 0) {
            $this->studentRepo->sincrireDunClub($_SESSION['user']['id'], $_GET['club_id'], true);
            $this->renderWithLayout('student/dashboard');
        } elseif ($nomreClub < 8) {
            $this->studentRepo->sincrireDunClub($_SESSION['user']['id'], $_GET['club_id']);
            $this->renderWithLayout('student/dashboard');
        } else {
            $error = "club contient maximum 8 membres";
            $this->renderWithLayout('student/dashboard', 'error', $error);
        }
    }


}