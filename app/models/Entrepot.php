<?php
namespace app\models;

USE PDO;

class Entrepot extends Model {
    protected $table = 'livraison_Entrepot';

    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->execute($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}