<?php
namespace app\controllers;

use app\models\Colis;

class ColisController {
    public function store(){
        if (!isset($_SESSION['logged_in'])) {
            http_response_code(401);
            exit('Non autorisé');
        }

        $description = $_POST['description'];
        $poids = $_POST['poids'];
        $prix = $_POST['prix'];

        $colisModel = new Colis();
        $colisModel->create($description, $poids, $prix);

        header('Location: /app/livraison');
        exit;
    }

}