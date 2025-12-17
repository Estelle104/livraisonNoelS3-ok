<?php
namespace app\models;

USE PDO;

class Livraison extends Model {
    protected $table = 'livraison_Livraison';

    public function getAll() {
        $sql = "SELECT l.*, 
                       c.descriptionColi, 
                       v.nomVehicule, 
                       ch.nomChauffeur,
                       e.nomEntrepot,
                       et.etatlivraison
                FROM {$this->table} l
                LEFT JOIN livraison_Colis c ON l.idColis = c.id
                LEFT JOIN livraison_Vehicules v ON l.idVehicule = v.id
                LEFT JOIN livraison_Chauffeur ch ON l.idChauffeur = ch.id
                LEFT JOIN livraison_Entrepot e ON l.idEntrepot = e.id
                LEFT JOIN livraison_EtatLivraison et ON l.idEtat = et.id
                ORDER BY l.dateLivraison DESC";
        // $sql = "SELECT * FROM livraison_v_HistoriqueBenefice ORDER BY dateLivraison DESC";
        $stmt = $this->execute($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (idColis, idEntrepot, destination, idVehicule, idEtat, idChauffeur, dateLivraison, coutVoiture, salaireJournalier)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['idColis'],
            $data['idEntrepot'],
            $data['destination'],
            $data['idVehicule'],
            $data['idEtat'] ?? 1, // EN_ATTENTE par défaut
            $data['idChauffeur'],
            $data['dateLivraison'],
            $data['coutVoiture'],
            $data['salaireChauffeur']
        ];
        
        $this->execute($sql, $params);
        return $this->getLastInsertId();
        header("Location:/app/livraison");
    }

    public function updateEtat($id, $idEtat) {
        $sql = "UPDATE {$this->table} SET idEtat = ? WHERE id = ?";
        $this->execute($sql, [$idEtat, $id]);
        return true;
    }

    public function find($id) {
        $sql = "SELECT l.*, 
                       c.descriptionColi, 
                       v.nomVehicule, 
                       ch.nomChauffeur,
                       e.nomEntrepot,
                       et.etatlivraison
                FROM {$this->table} l
                LEFT JOIN livraison_Colis c ON l.idColis = c.id
                LEFT JOIN livraison_Vehicules v ON l.idVehicule = v.id
                LEFT JOIN livraison_Chauffeur ch ON l.idChauffeur = ch.id
                LEFT JOIN livraison_Entrepot e ON l.idEntrepot = e.id
                LEFT JOIN livraison_EtatLivraison et ON l.idEtat = et.id
                WHERE l.id = ?";
        $stmt = $this->execute($sql, [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}