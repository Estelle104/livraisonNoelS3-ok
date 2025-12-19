<?php
namespace app\models;

USE PDO;


class Destination extends Model {
    protected $table = 'livraison_Destination';
    
    public function getAll() {
        $sql = "SELECT d.*, z.zoneLivraison, z.pourcentageZone 
                FROM {$this->table} d
                LEFT JOIN livraison_ZoneLivraison z ON d.idZoneLivraison = z.id
                ORDER BY d.nomDestination";
        $stmt = $this->execute($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}