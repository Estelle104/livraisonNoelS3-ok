<?php
namespace app\models;

USE Flight;
use PDO;
use PDOException;

abstract class Model {
    protected static $pdo = null;
    protected $table;

    public function __construct() {
        if (self::$pdo === null) {
            try {
                // Charge la configuration
                $config = require __DIR__ . '/../config/config.php';
                $db = $config['database'];
                
                // Connexion MySQL
                self::$pdo = new PDO(
                    'mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'] . ';charset=utf8',
                    $db['user'],
                    $db['password']
                );
                
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
            } catch (PDOException $e) {
                // Message d'erreur simple
                die("Erreur MySQL: " . $e->getMessage() . 
                    "<br>Vérifie que:<br>" .
                    "1. MySQL est démarré<br>" .
                    "2. La base 'livraisonNoelS3' existe<br>" .
                    "3. Config dans config.php est correcte");
            }
        }
    }

    protected function execute($sql, $params = []) {
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function getLastInsertId() {
        return self::$pdo->lastInsertId();
    }
}