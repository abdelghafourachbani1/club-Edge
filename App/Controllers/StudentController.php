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
}