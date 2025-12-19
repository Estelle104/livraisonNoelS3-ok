<?php
namespace app\controllers;

use app\models\Benefice;

class BeneficeController {
    public function index() {
        // session_start();
        
        if (!isset($_SESSION['logged_in'])) {
            header('Location: ' . BASE_URL . '/login');
            exit(); 
        }

        $beneficeModel = new Benefice();
        
        $filters = [];
        if (isset($_GET['jour'])) $filters['jour'] = $_GET['jour'];
        if (isset($_GET['mois'])) $filters['mois'] = $_GET['mois'];
        if (isset($_GET['annee'])) $filters['annee'] = $_GET['annee'];
        
        // filtres avec operateur
        if (isset($_GET['jour_op'])) $filters['jour_op'] = $_GET['jour_op'];
        if (isset($_GET['mois_op'])) $filters['mois_op'] = $_GET['mois_op'];
        if (isset($_GET['annee_op'])) $filters['annee_op'] = $_GET['annee_op'];

        $benefices = $beneficeModel->getAll($filters);
        $totalBenefice = $beneficeModel->getTotalBenefice($filters);

        include __DIR__ . '/../views/benefice.php';
    }

    public function details() {
        
        if (!isset($_SESSION['logged_in'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non autorisé']);
            exit();
        }

        $beneficeModel = new Benefice();
        
        $filters = [];
        if (isset($_GET['jour'])) $filters['jour'] = $_GET['jour'];
        if (isset($_GET['mois'])) $filters['mois'] = $_GET['mois'];
        if (isset($_GET['annee'])) $filters['annee'] = $_GET['annee'];

        $benefices = $beneficeModel->getAll($filters);

        header('Content-Type: application/json');
        echo json_encode($benefices);
    }
}