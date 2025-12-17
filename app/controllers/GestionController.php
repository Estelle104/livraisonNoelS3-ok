<?php
namespace app\controllers;

use app\models\MvtTrajet;
use Flight;

class GestionController {

    /**
     * Page de gestion (accueil des nouvelles fonctionnalités)
     */
    public static function index() {
        $date = $_GET['date'] ?? date('Y-m-d');
        $mois = $_GET['mois'] ?? date('m');
        $annee = $_GET['annee'] ?? date('Y');
        
        // Gérer le cas où mois et année pourraient être vides
        $mois = is_numeric($mois) ? $mois : date('m');
        $annee = is_numeric($annee) ? $annee : date('Y');
        
        $data = [
            'vehicules' => MvtTrajet::getVehiculesDisponiblesParDate($date),
            'panne' => MvtTrajet::getTauxPanneParMois(),
            'salaires' => MvtTrajet::getSalaireJournalierChauffeurs($date),
            'pourcentages' => MvtTrajet::getAllPourcentages(),
            'versementMinimum' => MvtTrajet::getVersementMinimum(),
            'dateSelectionnee' => $date,
            'moisSelectionne' => $mois,
            'anneeSelectionnee' => $annee
        ];
        
        Flight::render('gestion', $data);
    }

    /**
     * Page de vérification des salaires historiques
     */
    public static function verificationSalaire() {
        $dateDebut = $_GET['dateDebut'] ?? date('Y-m-01');
        $dateFin = $_GET['dateFin'] ?? date('Y-m-d');
        
        $data = [
            'salaireHistorique' => MvtTrajet::verifierSalaireHistorique($dateDebut, $dateFin),
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin
        ];
        
        Flight::render('verification_salaire', $data);
    }

    /**
     * Mettre à jour le versement minimum
     */
    public static function updateVersement() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $minVersement = $_POST['minVersement'] ?? 0;
            $idPourcentage = $_POST['idPourcentage'] ?? 1;
            
            MvtTrajet::updateVersementMinimum($minVersement, $idPourcentage);
            
            Flight::redirect('/gestion?success=1');
        }
    }
}