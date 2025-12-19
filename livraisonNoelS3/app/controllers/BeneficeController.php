<?php
namespace app\controllers;

use app\models\Benefice;
use app\models\Livraison;

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

    public function detailsPage($date) {
        // session_start();
        
        if (!isset($_SESSION['logged_in'])) {
            header('Location: ' . BASE_URL . '/login');
            exit(); 
        }

        // Convertir la date du format URL au format SQL
        $dateFormatted = date('Y-m-d', strtotime($date));
        
        // Récupérer les bénéfices pour cette date spécifique
        $beneficeModel = new Benefice();
        $filters = ['jour' => $dateFormatted, 'jour_op' => '='];
        $beneficesDuJour = $beneficeModel->getAll($filters);
        
        // Récupérer toutes les livraisons de cette date
        $livraisonModel = new Livraison();
        $livraisonsDuJour = $this->getLivraisonsParDate($dateFormatted);
        
        // Calculer les totaux
        $totalCA = array_sum(array_column($beneficesDuJour, 'chiffreAffaire'));
        $totalCout = array_sum(array_column($beneficesDuJour, 'coutRevient'));
        $totalBenefice = array_sum(array_column($beneficesDuJour, 'benefice'));

        include __DIR__ . '/../views/benefice_details.php';
    }

    private function getLivraisonsParDate($date) {
        $livraisonModel = new Livraison();
        
        // Méthode 1 : Si vous avez une méthode dans Livraison pour filtrer par date
        // return $livraisonModel->getByDate($date);
        
        // Méthode 2 : Récupérer toutes et filtrer
        $allLivraisons = $livraisonModel->getAll();
        $livraisonsDuJour = [];
        
        foreach ($allLivraisons as $livraison) {
            $livraisonDate = date('Y-m-d', strtotime($livraison['dateLivraison']));
            if ($livraisonDate == $date) {
                $livraisonsDuJour[] = $livraison;
            }
        }
        
        return $livraisonsDuJour;
    }

    // API endpoint pour les données JSON (optionnel)
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