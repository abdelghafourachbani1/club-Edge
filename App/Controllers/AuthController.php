<?php
namespace App\Controllers;

use App\Models\Database;
use App\Repositories\AuthRepository;
use App\Repositories\UserRepository;
use Controller;

class AuthController extends Controller
{

    public function showRegister(): void
    {
        $this->renderWithLayout('auth/register');
    }

    public function register(): void
    {
        $password  = $_POST['password'] ?? '';
$email     = $_POST['email'] ?? '';
$firstName = $_POST['first_name'] ?? '';
$lastName  = $_POST['last_name'] ?? '';

$errors = [];

$email     = trim($email);
$firstName = trim($firstName);
$lastName  = trim($lastName);

// if ($email === '' || $password === '' || $firstName === '' || $lastName === '') {
//     $errors[] = "All fields are required";
// }

// 3) Email (best: filter_var)
// if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
//     $errors[] = "Invalid email format";
// }

// 4) First/Last name regex (letters + spaces + - + ' , 2-50 chars)
// Supports accents: é, à, etc. (UTF-8)
// $nameRegex = "/^[\p{L}][\p{L}\p{M}\s'\-]{1,49}$/u";

// if ($firstName !== '' && !preg_match($nameRegex, $firstName)) {
//     $errors[] = "First name is invalid (letters only, 2-50 chars)";
// }

// if ($lastName !== '' && !preg_match($nameRegex, $lastName)) {
//     $errors[] = "Last name is invalid (letters only, 2-50 chars)";
// }

// 5) Password regex (min 8, at least 1 upper, 1 lower, 1 digit, 1 special)
// $passRegex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,64}$/";

// if ($password !== '' && !preg_match($passRegex, $password)) {
//     $errors[] = "Password must be 8-64 chars and include upper, lower, number, special";
// }

// 6) If errors -> render
// if (!empty($errors)) {
//     $this->render('auth/register', ['error' => implode('<br>', $errors)]);
//     return; // IMPORTANT: stop here
// }


        if (empty($email) || empty($password) || empty($firstName) || empty($lastName)) {
            $this->renderWithLayout('auth/register', ['error' => 'All fields are required']);
            return;
        }

        $userData = [
            'password' => password_hash($password, PASSWORD_DEFAULT),
            // 'password' => $password,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email
        ];

        $pdo = Database::getInstance();
        $repo = new AuthRepository();
        $id = $repo->createUser($userData);       
        if ($id) {
            // header('Location: login');
            header('Location: login'); // si tu gardes le prefix
            exit;
            // $this->renderWithLayout('auth/register', ['success' => 'User created successfully']);
        }else{
            $this->renderWithLayout('auth/register', ['error' => 'something wrong repeated register']);
        }
    }

    public function showLogin(): void
    {
        $this->renderWithLayout('auth/login');
    }

    public function login(): void 
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        if (empty($email) || empty($password)) {
            $this->renderWithLayout('auth/login', ['error' => 'All fields are required']);
            return;
        }
        $pdo = Database::getInstance();
        $userRepo = new UserRepository($pdo);
        // $error = null;

        // $user = $userRepo->findByEmail($email);
        $user = $userRepo->findByEmail($email);
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => (int)$user['id'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'email'=> $user['email'],
                    'role'=> $user['is_admin'] ? 'admin' : 'student',
                ];
                if ($user['is_admin'] === true) {
                    header('location: admin/dashboard');
                    exit;
                } else {
                    header('location: student/dashboard');
                    exit;
                }
                // $this->renderWithLayout('auth/login', ['user' => $user]);
            } else {
                $error =  "Invalid password";
                $this->renderWithLayout('auth/login', ['error' => $error]);
            }
        } 
        else {
            // $error =  "User not found";
            $error =  "email incorrect";
            $this->renderWithLayout('auth/login', ['error' => $error]);

        }
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /club-Edge/login');
        exit;
    }
}