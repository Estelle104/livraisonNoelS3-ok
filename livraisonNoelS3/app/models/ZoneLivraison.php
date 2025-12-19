<?php
namespace app\models;

use PDO;

class ZoneLivraison extends Model
{
    protected $table = 'livraison_ZoneLivraison';

    public function getAll(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $stmt = $this->execute($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO {$this->table} (zoneLivraison, pourcentageZone)
                VALUES (?, ?)";

        $this->execute($sql, [
            $data['zoneLivraison'],
            $data['pourcentageZone']
        ]);

        return $this->getLastInsertId();
    }

    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->execute($sql, [$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table}
                SET zoneLivraison = ?, pourcentageZone = ?
                WHERE id = ?";

        $this->execute($sql, [
            $data['zoneLivraison'],
            $data['pourcentageZone'],
            $id
        ]);

        return true;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $this->execute($sql, [$id]);
        return true;
    }
}
