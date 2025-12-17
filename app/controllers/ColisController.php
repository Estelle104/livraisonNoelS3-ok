<?php
namespace app\controllers;

use app\models\Colis;

class ColisController {
    public function store() {
        session_start();
        
        if (!isset($_SESSION['logged_in'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non autorisé']);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $descriptionColi = $_POST['descriptionColi'] ?? '';
            $poidsColis = $_POST['poidsColis'] ?? 0;
            $prixUnitaire = $_POST['prixUnitaire'] ?? 0;

            $colisModel = new Colis();
            $id = $colisModel->create($descriptionColi, $poidsColis, $prixUnitaire);

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'id' => $id]);
        }
    }
}