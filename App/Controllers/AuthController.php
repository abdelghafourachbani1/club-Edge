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
        // Simple validation example, in a real app use a Validator class
        // $password = $_POST['password'] ?? '';
        $password = $_POST['password'];
        $email = $_POST['email'];
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];

        // if (empty($email) || empty($password)) {
        //     // Handle error, for now just re-render with error (not implemented in view yet)
        //     $this->render('auth/register', ['error' => 'Email and password are required']);
        //     return;
        // }

        $userData = [
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email
        ];

        // $userData = [
        //     'first_name' => 'Younes',
        //     'last_name'  => 'Bahmoun',
        //     'email'      => 'younes@gmail.com',
        //     'password'   => password_hash('123456', PASSWORD_DEFAULT),
        // ];
        $pdo = Database::getInstance();
        $repo = new AuthRepository();
        $id = $repo->createUser($userData);       
        // $id = $repo->allUsers();       
        // $id = 3;
        if ($id) {
            // $_SESSION['user'] = [
            //     'id'         => (int)$id,
            //     'first_name' => $firstName,
            //     'last_name'  => $lastName,
            //     'email'      => $email,
            // ];
            $this->renderWithLayout('auth/login');
        }else{
            $this->renderWithLayout('auth/register', ['error' => 'Failed to create user']);
        }
        // Pass $id to view to trigger the success message
        $this->renderWithLayout('auth/register', ['id' => $id]);
    }

    public function showLogin(): void
    {
        // TODO: Create a login view
        $this->renderWithLayout('auth/login');
    }

    public function login(): void 
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
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
                $this->renderWithLayout('auth/login', ['user' => $user]);
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
        $this->renderWithLayout('auth/login');
    }
}