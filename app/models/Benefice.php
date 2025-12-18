<?php
namespace app\models;

use PDO;

class Benefice extends Model {
    protected $table = 'livraison_v_HistoriqueBenefice';

    public function getAll($filters = []) {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        // CORRECTION : Votre vue a 'jour' comme DATE, pas comme numéro
        // Si vous voulez filtrer par numéro de jour, utilisez DAY(jour)

        if (!empty($filters['jour'])) {
            $op = $filters['jour_op'] ?? '=';
            
            // OPTION 1 : Si 'jour' dans la vue est DATE
            $sql .= " AND DAY(jour) $op ?";
            // OPTION 2 : Si 'jour' dans la vue est déjà un numéro (1-31)
            // $sql .= " AND jour $op ?"; // UNIQUEMENT si c'est un numéro
            $params[] = $filters['jour'];
        }

        if (!empty($filters['mois'])) {
            $op = $filters['mois_op'] ?? '=';
            $sql .= " AND mois $op ?";
            $params[] = $filters['mois'];
        }

        if (!empty($filters['annee'])) {
            $op = $filters['annee_op'] ?? '=';
            $sql .= " AND annee $op ?";
            $params[] = $filters['annee'];
        }

        $sql .= " ORDER BY jour DESC";
        
        $stmt = $this->execute($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalBenefice($filters = []) {
        $sql = "SELECT SUM(benefice) as total_benefice FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['jour'])) {
            $op = $filters['jour_op'] ?? '=';
            // Même correction ici
            $sql .= " AND DAY(jour) $op ?";
            $params[] = $filters['jour'];
        }

        if (!empty($filters['mois'])) {
            $op = $filters['mois_op'] ?? '=';
            $sql .= " AND mois $op ?";
            $params[] = $filters['mois'];
        }

        if (!empty($filters['annee'])) {
            $op = $filters['annee_op'] ?? '=';
            $sql .= " AND annee $op ?";
            $params[] = $filters['annee'];
        }

        $stmt = $this->execute($sql, $params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_benefice'] ?? 0;
    }
}