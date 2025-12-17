<?php
namespace app\models;

use Flight;

class MvtTrajet {

    public static function getAllChauffeurs() {
        return Flight::db()->query("SELECT * FROM tbChauffeurs")->fetchAll();
    }

    public static function getAllVehicules() {
        return Flight::db()->query("SELECT * FROM tbVehicules")->fetchAll();
    }

    public static function getAllTrajets() {
        return Flight::db()->query("SELECT * FROM tbTrajets")->fetchAll();
    }

    public static function save($data) {
        $sql = "INSERT INTO tbMvtTrajet(dateDebut,dateFin,idChauffeur,idVehicule,idTrajet,montantRecette,montantCarburant,panne)
                VALUES (:dateDebut,:dateFin,:idChauffeur,:idVehicule,:idTrajet,:recette,:carburant,:panne)";

        $stm = Flight::db()->prepare($sql);
        return $stm->execute($data);
    }

    // ============ NOUVELLES METHODES POUR LA PAGE 3 ============

    /**
     * Récupérer toutes les statistiques par jour, véhicule et chauffeur
     */
    public static function getStatsByDayVehicleAndDriver() {
        $sql = "SELECT * FROM v_listParJourVehiculesEtChauffeur";
        return Flight::db()->query($sql)->fetchAll();
    }

    /**
     * Récupérer le bénéfice total par jour
     */
    public static function getDailyBenefitSummary() {
        $sql = "SELECT 
                    jour,
                    SUM(montantRecette) as totalRecette,
                    SUM(montantCarburant) as totalCarburant,
                    SUM(benefice) as totalBenefice,
                    SUM(kilometreEffectue) as totalKilometres
                FROM v_listParJourVehiculesEtChauffeur
                GROUP BY jour
                ORDER BY jour DESC";
        return Flight::db()->query($sql)->fetchAll();
    }

    /**
     * Récupérer le bénéfice par véhicule
     */
    public static function getBenefitByVehicle() {
        $sql = "SELECT 
                    nomVehicule,
                    SUM(montantRecette) as totalRecette,
                    SUM(montantCarburant) as totalCarburant,
                    SUM(benefice) as totalBenefice,
                    SUM(kilometreEffectue) as totalKilometres
                FROM v_listParJourVehiculesEtChauffeur
                GROUP BY nomVehicule
                ORDER BY totalBenefice DESC";
        return Flight::db()->query($sql)->fetchAll();
    }

    /**
     * Récupérer les détails des bénéfices par jour
     */
    public static function getDailyBenefitDetails() {
        $sql = "SELECT 
                    jour,
                    nomVehicule,
                    nomChauffeur,
                    kilometreEffectue,
                    montantRecette,
                    montantCarburant,
                    benefice
                FROM v_listParJourVehiculesEtChauffeur
                ORDER BY jour DESC, benefice DESC";
        return Flight::db()->query($sql)->fetchAll();
    }

        /**
     * Véhicules disponibles à une date donnée
     */
   public static function getVehiculesDisponiblesParDate($date) {
    $sql = "
        SELECT v.id, v.nomVehicule
        FROM tbVehicules v
        WHERE NOT EXISTS (
            SELECT 1
            FROM v_listParJourVehiculesEtChauffeur vw
            WHERE vw.idVehicule = v.id
            AND vw.jour = ?
        )
    ";

    $stmt = Flight::db()->prepare($sql);
    $stmt->execute([$date]);
    return $stmt->fetchAll();
}




        /**
     * Taux de panne par mois et par véhicule
     */
    public static function getTauxPanneParMois() {
        $sql = "
            SELECT 
                nomVehicule,
                mois,
                annee,
                jours_travailles,
                jours_panne,
                ROUND((jours_panne / jours_travailles) * 100, 2) AS taux_panne
            FROM view_taux_panne_mois
            ORDER BY annee DESC, mois DESC
        ";

        return Flight::db()->query($sql)->fetchAll();
    }

        /**
     * Salaire journalier par chauffeur
     */
    public static function getSalaireJournalierChauffeurs() {
        $sql = "
            SELECT 
                nomChauffeur,
                date_trajet,
                recette_totale,
                minVersement,
                pourcentage_sup_minimum
            FROM view_salaire_journalier
            ORDER BY date_trajet DESC
        ";

        return Flight::db()->query($sql)->fetchAll();
    }

    /**
     * Récupérer tous les pourcentages
     */
    public static function getAllPourcentages() {
        return Flight::db()->query("SELECT * FROM tbPourcentages")->fetchAll();
    }

    /**
     * Dernier versement minimum
     */
    public static function getVersementMinimum() {
        $sql = "SELECT * FROM tbVersements ORDER BY date_creation DESC LIMIT 1";
        return Flight::db()->query($sql)->fetch();
    }

    /**
     * Mise à jour du versement minimum
     */
    public static function updateVersementMinimum($minVersement, $idPourcentage) {
        $sql = "
            INSERT INTO tbVersements (minVersement, idPourcentage)
            VALUES (?, ?)
        ";

        $stmt = Flight::db()->prepare($sql);
        return $stmt->execute([$minVersement, $idPourcentage]);
    }

    
}