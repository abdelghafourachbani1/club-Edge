<?php
namespace App\Controllers;

use App\Models\Database;
use App\Repositories\AuthRepository;
use App\Repositories\UserRepository;
use Controller;

class StudentController extends Controller
{
    public function dashboard(): void {
        $this->renderWithLayout('student/dashboard', ['user' => $_SESSION['user']]);
    }
}