<?php
namespace app\models;

USE PDO;

class Colis extends Model {
    protected $table = 'livraison_Colis';

    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->execute($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($descriptionColi, $poidsColis, $prixUnitaire) {
        $sql = "INSERT INTO {$this->table} (descriptionColi, poidsColis, prixUnitaire) VALUES (?, ?, ?)";
        $this->execute($sql, [$descriptionColi, $poidsColis, $prixUnitaire]);
        return $this->getLastInsertId();
    }

    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->execute($sql, [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}