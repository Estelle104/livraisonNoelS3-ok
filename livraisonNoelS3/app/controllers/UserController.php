<?php
namespace app\controllers;

use app\models\User;

class UserController {
    public function loginForm() {
        include __DIR__ . '/../views/login.php';
    }

    public function login() {
        // session_start();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $loginUser = $_POST['loginUser'] ?? '';
            $mdp = $_POST['mdp'] ?? '';

            $userModel = new User();
            $user = $userModel->checkLogin($loginUser, $mdp);

            if ($user) {
                $_SESSION['user'] = $user;
                $_SESSION['logged_in'] = true;
                header('Location: /app/accueil');
                exit();
            } else {
                $_SESSION['error'] = 'Identifiants incorrects';
                header('Location: /login');
                exit();
            }
        }
    }

    public function home() {
        // session_start();
        
        if (!isset($_SESSION['logged_in'])) {
            header('Location: /login');
            exit();
        }

        include __DIR__ . '/../views/accueil.php';
    }

    public function logout() {
        // session_start();
        session_destroy();
        header('Location: /login');
        exit();
    }
}