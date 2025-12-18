<?php
namespace app\models;

USE PDO;

class Chauffeur extends Model {
    protected $table = 'livraison_Chauffeur';

    public function getAll() {
        $sql = "SELECT c.*, s.nomSociete 
                FROM {$this->table} c
                LEFT JOIN livraison_Societes s ON c.idSociete = s.id";
        $stmt = $this->execute($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->execute($sql, [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}