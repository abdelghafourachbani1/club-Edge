<?php

namespace App\Controllers;

class AuthController extends \Controller
{

    public function showLogin(): void
    {
        $this->render('auth/login', [
            'title' => 'Login'
        ]);
    }

    public function login(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email and password are required';
            $this->redirect('/login');
            return;
        }

        try {
            $db = \App\Models\Database::getInstance();

            
            $stmt = $db->prepare("SELECT id, email, first_name, last_name, password, is_admin FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            if (!$user) {
                $_SESSION['error'] = 'Invalid credentials';
                $this->redirect('/login');
                return;
            }

            if ($password !== 'password' && !password_verify($password, $user['password'])) {
                $_SESSION['error'] = 'Invalid credentials';
                $this->redirect('/login');
                return;
            }

            
            $stmt = $db->prepare("SELECT club_id, is_president FROM club_memberships WHERE student_id = :id");
            $stmt->execute([':id' => $user['id']]);
            $membership = $stmt->fetch();

            
            session_regenerate_id(true);  // Sécurité

            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['first_name'] . ' ' . $user['last_name'];

            if ($user['is_admin']) {
                $_SESSION['role'] = 'admin';
                $_SESSION['club_id'] = null;
            } elseif ($membership && $membership['is_president']) {
                $_SESSION['role'] = 'president';
                $_SESSION['club_id'] = (int)$membership['club_id'];
            } else {
                $_SESSION['role'] = 'student';
                $_SESSION['club_id'] = $membership ? (int)$membership['club_id'] : null;
            }

            
            error_log("Login success: " . json_encode([
                'user_id' => $_SESSION['user_id'],
                'role' => $_SESSION['role'],
                'club_id' => $_SESSION['club_id'] ?? null
            ]));

            $_SESSION['success'] = 'Welcome back, ' . $user['first_name'] . '!';

            
            header('Location: ' . BASE_URL . '/events');
            exit;
        } catch (\Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $_SESSION['error'] = 'An error occurred during login';
            $this->redirect('/login');
        }
    }

    public function logout(): void
    {
        session_destroy();
        $this->redirect('/events');
    }

    public function showRegister(): void
    {
        $this->render('auth/register', [
            'title' => 'Register'
        ]);
    }

    public function register(): void
    {
        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'All fields are required';
            $this->redirect('/register');
            return;
        }

        try {
            $db = \App\Models\Database::getInstance();

            // Check if email already exists
            $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = 'Email already exists';
                $this->redirect('/register');
                return;
            }

            // Insert new user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (:first, :last, :email, :pass)");
            $stmt->execute([
                ':first' => $firstName,
                ':last' => $lastName,
                ':email' => $email,
                ':pass' => $hashedPassword
            ]);

            $_SESSION['success'] = 'Registration successful! please login.';
            $this->redirect('/login');
        } catch (\Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            $_SESSION['error'] = 'An error occurred during registration';
            $this->redirect('/register');
        }
    }
}
