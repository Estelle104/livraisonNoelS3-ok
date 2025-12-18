<?php
namespace app\models;

USE PDO;

class Vehicule extends Model {
    protected $table = 'livraison_Vehicules';

    public function getAll() {
        $sql = "SELECT v.*, s.nomSociete, tv.poidsMax 
                FROM {$this->table} v
                LEFT JOIN livraison_Societes s ON v.idSociete = s.id
                LEFT JOIN livraison_TypeVehicules tv ON v.idTypeVehicule = tv.id";
        $stmt = $this->execute($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->execute($sql, [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}