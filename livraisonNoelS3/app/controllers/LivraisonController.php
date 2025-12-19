<?php
namespace app\controllers;

use app\models\Livraison;
use app\models\Colis;
use app\models\Vehicule;
use app\models\Chauffeur;
use app\models\Entrepot;
use app\models\EtatLivraison;
use app\models\Destination;

class LivraisonController {
    public function index() {
        // session_start();
        
        if (!isset($_SESSION['logged_in'])) {
            header('Location:' . BASE_URL . '/login');
            exit();
        }

        $livraisonModel = new Livraison();
        $colisModel = new Colis();
        $vehiculeModel = new Vehicule();
        $chauffeurModel = new Chauffeur();
        $entrepotModel = new Entrepot();
        $destinationModel = new Destination();

        $livraisons = $livraisonModel->getAll();
        $colis = $colisModel->getAll();
        $vehicules = $vehiculeModel->getAll();
        $chauffeurs = $chauffeurModel->getAll();
        $entrepots = $entrepotModel->getAll();
        $destinations = $destinationModel->getAll();

        include __DIR__ . '/../views/gestionLivraison.php';
    }

    public function show($id) {
        // session_start();
        
        if (!isset($_SESSION['logged_in'])) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        $livraisonModel = new Livraison();
        $livraison = $livraisonModel->find($id);

        header('Content-Type: application/json');
        echo json_encode($livraison);
    }

    public function store() {
        // session_start();
        
        if (!isset($_SESSION['logged_in'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non autorisé']);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'idColis' => $_POST['idColis'] ?? null,
                'idEntrepot' => $_POST['idEntrepot'] ?? null,
                'idDestination' => $_POST['idDestination'] ?? '',
                'idVehicule' => $_POST['idVehicule'] ?? null,
                'idEtat' => 1, // EN_ATTENTE
                'idChauffeur' => $_POST['idChauffeur'] ?? null,
                'dateLivraison' => $_POST['dateLivraison'] ?? date('Y-m-d H:i:s'),
                'coutVoiture' => $_POST['coutVoiture'] ?? 0,
                'salaireChauffeur' => $_POST['salaireChauffeur'] ?? 0
            ];

            $livraisonModel = new Livraison();
            $id = $livraisonModel->create($data);

            header('Location: ' . BASE_URL . '/livraison');
            exit;
        }
    }

    public function update($id) {
        // session_start();
        
        if (!isset($_SESSION['logged_in'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non autorisé']);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            parse_str(file_get_contents("php://input"), $putData);
            
            $livraisonModel = new Livraison();
            
            if (isset($putData['idEtat'])) {
                $livraisonModel->updateEtat($id, $putData['idEtat']);
            }

            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        }
    }
    public function updateEtat($id){
        if (!isset($_SESSION['logged_in'])) {
            http_response_code(401);
            echo "Non autorisé";
            exit;
        }

        if (!isset($_POST['idEtat'])) {
            http_response_code(400);
            echo "État manquant";
            exit;
        }

        $livraisonModel = new Livraison();
        $livraisonModel->updateEtat($id, $_POST['idEtat']);

        // Retour a liste livraison
        header('Location: ' . BASE_URL . '/livraison');
        exit;
    }


    public function delete($id) {
        // session_start();
        
        if (!isset($_SESSION['logged_in'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non autorisé']);
            exit();
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    }

    public function deleteAll() {
        // session_start();

        $code = $_POST['code'] ?? null;
        
        if (!isset($_SESSION['logged_in'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non autorisé']);
            exit();
        }
        if($code === null){
            http_response_code(400);
            echo json_encode(['error' => 'Code manquant']);
            exit();
        }
        if($code !== '9999'){
            http_response_code(403);
            echo json_encode(['error' => 'Code incorrect']);
            exit();
        }

        if ($code = '9999') {
            $livraisonModel = new Livraison();
            $livraisonModel->deleteAll();
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        }
    }
}