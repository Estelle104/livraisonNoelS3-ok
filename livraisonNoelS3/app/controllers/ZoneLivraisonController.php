<?php
namespace app\controllers;

use app\models\ZoneLivraison;
use Flight;

class ZoneLivraisonController
{
    private ZoneLivraison $zone;

    public function __construct()
    {
        $this->zone = new ZoneLivraison();
    }

    
    public function index()
    {
        if (!isset($_SESSION['logged_in'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $zones = $this->zone->getAll();

        include __DIR__ . '/../views/gestionZone.php';
    }

    public function store()
    {
        if (!isset($_SESSION['logged_in'])) {
            http_response_code(401);
            exit('Non autorisé');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Méthode non autorisée');
        }

        $zoneLivraison  = trim($_POST['zoneLivraison'] ?? '');
        $pourcentage    = $_POST['pourcentageZone'] ?? null;

        if ($zoneLivraison === '' || $pourcentage === null) {
            header('Location: /zones');
            exit;
        }

        $data = [
            'zoneLivraison'   => $zoneLivraison,
            'pourcentageZone' => $pourcentage
        ];

        $this->zone->create($data);

        Flight::redirect('/zones');
    }

    public function edit($id)
    {
        if (!isset($_SESSION['logged_in'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $zone = $this->zone->find($id);

        if (!$zone) {
            Flight::redirect('/zones');
            return;
        }

        include __DIR__ . '/../views/editZone.php';
    }

    public function update($id)
    {
        if (!isset($_SESSION['logged_in'])) {
            http_response_code(401);
            exit('Non autorisé');
        }

        $data = [
            'zoneLivraison'   => $_POST['zoneLivraison'] ?? '',
            'pourcentageZone' => $_POST['pourcentageZone'] ?? 0
        ];

        $this->zone->update($id, $data);

        Flight::redirect('/zones');
    }

    public function delete($id)
    {
        if (!isset($_SESSION['logged_in'])) {
            http_response_code(401);
            exit('Non autorisé');
        }

        $this->zone->delete($id);

        Flight::redirect('/zones');
    }
}
