<?php
namespace app\models;

class EtatLivraison extends Model {
    protected $table = 'livraison_EtatLivraison';

    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->execute($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}