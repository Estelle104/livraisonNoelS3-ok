<?php
namespace app\models;

use Flight;

/**
 * Rapports journaliers / par véhicule / chauffeur
 */
class Report {

    /**
     * Liste par jour / véhicule / chauffeur
     * Retourne lignes : date, idVehicule, nomVehicule, idChauffeur, nomChauffeur,
     * sum_km, sum_recette, sum_carburant, benefice
     */
    public static function getv_listParJourVehiculesEtChauffeur() {
        $sql = "
            SELECT
                DATE(m.dateDebut) AS jour,
                m.idVehicule,
                v.nomVehicule,
                m.idChauffeur,
                c.nomChauffeur,
                SUM(t.distance) AS sum_kilometreEffectue,
                SUM(m.montantRecette) AS sum_montantRecette,
                SUM(m.montantCarburant) AS sum_montantCarburant,
                SUM(m.montantRecette) - SUM(m.montantCarburant) AS benefice
            FROM tbMvtTrajet m
            JOIN tbTrajets t ON t.id = m.idTrajet
            JOIN tbVehicules v ON v.id = m.idVehicule
            JOIN tbChauffeurs c ON c.id = m.idChauffeur
            GROUP BY DATE(m.dateDebut), m.idVehicule, m.idChauffeur
            ORDER BY DATE(m.dateDebut) DESC, v.nomVehicule, c.nomChauffeur
        ";
        return Flight::db()->query($sql)->fetchAll();
    }

    /**
     * Bénéfice par jour (totaux globaux par jour)
     * Retourne : jour, sum_kilometreEffectue, sum_recette, sum_carburant, beneficeParJour
     */
    public static function getv_listParJourVehiculesEtChauffeurBenefice() {
        $sql = "
            SELECT
                DATE(m.dateDebut) AS jour,
                SUM(t.distance) AS sum_kilometreEffectue,
                SUM(m.montantRecette) AS sum_montantRecette,
                SUM(m.montantCarburant) AS sum_montantCarburant,
                SUM(m.montantRecette) - SUM(m.montantCarburant) AS beneficeParJour
            FROM tbMvtTrajet m
            JOIN tbTrajets t ON t.id = m.idTrajet
            GROUP BY DATE(m.dateDebut)
            ORDER BY DATE(m.dateDebut) DESC
        ";
        return Flight::db()->query($sql)->fetchAll();
    }

    /**
     * Bénéfice par véhicule (totaux globaux par véhicule sur toute la période)
     * Retourne : idVehicule, nomVehicule, sum_kilometreEffectue, sum_recette, sum_carburant, beneficeParVehicule
     */
    public static function getv_listParJourVehiculesEtChauffeurParVehicule() {
        $sql = "
            SELECT
                m.idVehicule,
                v.nomVehicule,
                SUM(t.distance) AS sum_kilometreEffectue,
                SUM(m.montantRecette) AS sum_montantRecette,
                SUM(m.montantCarburant) AS sum_montantCarburant,
                SUM(m.montantRecette) - SUM(m.montantCarburant) AS beneficeParVehicule
            FROM tbMvtTrajet m
            JOIN tbTrajets t ON t.id = m.idTrajet
            JOIN tbVehicules v ON v.id = m.idVehicule
            GROUP BY m.idVehicule, v.nomVehicule
            ORDER BY beneficeParVehicule DESC
        ";
        return Flight::db()->query($sql)->fetchAll();
    }

    /**
     * Trajets les plus rentables par jour
     * On calcule pour chaque jour le trajet (idTrajet) qui donne le plus grand benefice (recette - carburant)
     *
     * Note: la requête utilise une sous-requête pour compatibilité MySQL ancienne.
     */
    public static function getTopTrajetsParJour($limitPerDay = 1) {
        // Sous-requête : pour chaque jour et trajet, calculer profit total
        $sql = "
            SELECT
                jour, idTrajet, pointDepart, pointArrive,
                sum_km, sum_recette, sum_carburant, (sum_recette - sum_carburant) AS benefice
            FROM (
                SELECT
                    DATE(m.dateDebut) AS jour,
                    m.idTrajet,
                    t.pointDepart,
                    t.pointArrive,
                    SUM(t.distance) AS sum_km,
                    SUM(m.montantRecette) AS sum_recette,
                    SUM(m.montantCarburant) AS sum_carburant
                FROM tbMvtTrajet m
                JOIN tbTrajets t ON t.id = m.idTrajet
                GROUP BY DATE(m.dateDebut), m.idTrajet
            ) AS daily_trajets
            -- Ici on sélectionne les trajets qui sont top par jour :
            WHERE (jour, (sum_recette - sum_carburant)) IN (
                SELECT jour, MAX(sum_recette - sum_carburant) FROM (
                    SELECT DATE(m2.dateDebut) AS jour, m2.idTrajet, SUM(m2.montantRecette) AS sum_recette, SUM(m2.montantCarburant) AS sum_carburant
                    FROM tbMvtTrajet m2
                    GROUP BY DATE(m2.dateDebut), m2.idTrajet
                ) AS s
                GROUP BY jour
            )
            ORDER BY jour DESC, benefice DESC
        ";
        return Flight::db()->query($sql)->fetchAll();
    }
}
