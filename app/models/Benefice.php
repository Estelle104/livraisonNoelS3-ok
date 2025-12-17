<?php
namespace app\models;

USE PDO;

class Benefice extends Model {
    protected $table = 'livraison_v_HistoriqueBenefice';

    public function getAll($filters = []) {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['jour'])) {
            $sql .= " AND jour = ?";
            $params[] = $filters['jour'];
        }

        if (!empty($filters['mois'])) {
            $sql .= " AND mois = ?";
            $params[] = $filters['mois'];
        }

        if (!empty($filters['annee'])) {
            $sql .= " AND annee = ?";
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
            $sql .= " AND jour = ?";
            $params[] = $filters['jour'];
        }

        if (!empty($filters['mois'])) {
            $sql .= " AND mois = ?";
            $params[] = $filters['mois'];
        }

        if (!empty($filters['annee'])) {
            $sql .= " AND annee = ?";
            $params[] = $filters['annee'];
        }

        $stmt = $this->execute($sql, $params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_benefice'] ?? 0;
    }
}