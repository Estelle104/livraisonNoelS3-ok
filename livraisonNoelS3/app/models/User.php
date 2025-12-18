<?php
namespace app\models;

USE PDO;

class User extends Model {
    protected $table = 'livraison_User';

    public function checkLogin($loginUser, $mdp) {
        $sql = "SELECT * FROM {$this->table} WHERE loginUser = ? AND mdp = ?";
        $stmt = $this->execute($sql, [$loginUser, $mdp]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->execute($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}