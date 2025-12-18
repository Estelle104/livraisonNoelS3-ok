<?php
namespace app\controllers;

use app\models\MvtTrajet;
use Flight;

class MvtTrajetController {

    public static function form() {
        $data = [
            'chauffeurs' => MvtTrajet::getAllChauffeurs(),
            'vehicules' => MvtTrajet::getAllVehicules(),
            'trajets' => MvtTrajet::getAllTrajets()
        ];
        Flight::render('mvtTrajet', $data);
    }

    public static function save() {
        $data = [
            'dateDebut' => $_POST['dateDebut'],
            'dateFin' => $_POST['dateFin'],
            'idChauffeur' => $_POST['idChauffeur'],
            'idVehicule' => $_POST['idVehicule'],
            'idTrajet' => $_POST['idTrajet'],
            'recette' => $_POST['montantRecette'],
            'carburant' => $_POST['montantCarburant'],
            'panne' => $_POST['panne'] ?? null,
        ];

        MvtTrajet::save($data);

        Flight::redirect('/mvtTrajet?success=1');
    }

    // ============ NOUVELLE METHODE POUR LA PAGE 3 ============
    
    public static function stats() {
        $data = [
            'stats' => MvtTrajet::getStatsByDayVehicleAndDriver(),
            'dailyBenefit' => MvtTrajet::getDailyBenefitSummary(),
            'vehicleBenefit' => MvtTrajet::getBenefitByVehicle(),
            'dailyBenefitDetails' => MvtTrajet::getDailyBenefitDetails()
        ];
        
        Flight::render('stats', $data);
    }
}