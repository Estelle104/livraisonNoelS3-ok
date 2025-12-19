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
                       ch.salaire,
                       e.nomEntrepot,
                       et.etatlivraison,
                       d.nomDestination,
                       d.idZoneLivraison,
                       z.zoneLivraison,
                       z.pourcentageZone
                FROM {$this->table} l
                LEFT JOIN livraison_Colis c ON l.idColis = c.id
                LEFT JOIN livraison_Vehicules v ON l.idVehicule = v.id
                LEFT JOIN livraison_Chauffeur ch ON l.idChauffeur = ch.id
                LEFT JOIN livraison_Entrepot e ON l.idEntrepot = e.id
                LEFT JOIN livraison_EtatLivraison et ON l.idEtat = et.id
                LEFT JOIN livraison_Destination d ON l.idDestination = d.id
                LEFT JOIN livraison_ZoneLivraison z ON d.idZoneLivraison = z.id
                ORDER BY l.dateLivraison DESC";
        $stmt = $this->execute($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (idColis, idEntrepot, idDestination, idVehicule, idEtat, idChauffeur, dateLivraison, coutVoiture, salaireJournalier)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['idColis'],
            $data['idEntrepot'],
            $data['idDestination'],
            $data['idVehicule'],
            $data['idEtat'] ?? 1, // EN_ATTENTE par defaut
            $data['idChauffeur'],
            $data['dateLivraison'],
            $data['coutVoiture'],
            $data['salaireChauffeur']
        ];

        $this->execute($sql, $params);
        return $this->getLastInsertId();
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
                       et.etatlivraison,
                       d.nomDestination
                FROM {$this->table} l
                LEFT JOIN livraison_Colis c ON l.idColis = c.id
                LEFT JOIN livraison_Vehicules v ON l.idVehicule = v.id
                LEFT JOIN livraison_Chauffeur ch ON l.idChauffeur = ch.id
                LEFT JOIN livraison_Entrepot e ON l.idEntrepot = e.id
                LEFT JOIN livraison_EtatLivraison et ON l.idEtat = et.id
                LEFT JOIN livraison_Destination d ON l.idDestination = d.id
                WHERE l.id = ?";
        $stmt = $this->execute($sql, [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteAll() {
        $sql = "DELETE FROM {$this->table}";
        $this->execute($sql);
        return true;
    }
}