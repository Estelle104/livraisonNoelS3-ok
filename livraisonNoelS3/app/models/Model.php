<?php
namespace app\models;

use Flight;
use PDO;

abstract class Model {

    protected static $pdo = null;
    protected $table;

    public function __construct() {
        if (self::$pdo === null) {
            // recupere DB depuis Flight (services.php)
            self::$pdo = Flight::db();
        }
    }

    protected function execute(string $sql, array $params = []) {
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function getLastInsertId() {
        return self::$pdo->lastInsertId();
    }
}
