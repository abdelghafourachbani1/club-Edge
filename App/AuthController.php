<?php
namespace App\Controllers;

use App\Models\Database;
use App\Repositories\AuthRepository;
use Controller;

class AuthController extends Controller
{

    public function showRegister(): void
    {
        $this->render('auth/register');
    }

    public function register(): void
    {
        // Simple validation example, in a real app use a Validator class
        // $password = $_POST['password'] ?? '';
        // $email = $_POST['email'] ?? '';
        // $firstName = $_POST['first_name'] ?? '';
        // $lastName = $_POST['last_name'] ?? '';

        // if (empty($email) || empty($password)) {
        //     // Handle error, for now just re-render with error (not implemented in view yet)
        //     $this->render('auth/register', ['error' => 'Email and password are required']);
        //     return;
        // }

        // $userData = [
        //     'password' => password_hash($password, PASSWORD_DEFAULT),
        //     'first_name' => $firstName,
        //     'last_name' => $lastName,
        //     'email' => $email
        // ];

        $userData = [
            'first_name' => 'Younes',
            'last_name'  => 'Bahmoun',
            'email'      => 'younes@gmail.com',
            'password'   => password_hash('123456', PASSWORD_DEFAULT),
        ];
        $pdo = Database::getInstance();
        // $repo = new AuthRepository();
        $id = $repo->createUser($userData);       
        $id = $repo->allUsers();       
        // $id = 3;
                
        // Pass $id to view to trigger the success message
        $this->renderWithLayout('auth/register', ['id' => $id]);
    }

    public function showLogin(): void
    {
        // TODO: Create a login view
        echo "Login Page"; 
    }

    public function login(): void 
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->authRepository->findByEmail($email);
        if ($user) {
            if (password_verify($password, $user['password'])) {
                echo "Login successful";
            } else {
                echo "Invalid password";
            }
        } else {
            echo "User not found";
        }
    }

    public function logout(): void
    {
        // TODO: Implement logout
        echo "Logout";
    }
}