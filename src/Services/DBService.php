<?php

namespace TinyFramework\Services;

use mysqli;

class DBService
{
    public ?mysqli $connection = null;

    public function __construct()
    {
        $config = config();

        if (empty($config['database'])) {
            return;
        }

        $this->connection = new mysqli(
            $config['database']['host'],
            $config['database']['username'],
            $config['database']['password'],
            $config['database']['database'],
            $config['database']['port'],
        );

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function querySelect(string $sql): array
    {
        $result = $this->connection->query($sql);
        $records = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $records[] = $row;
            }
        } else {
            return [];
        }

        return $records;
    }

    public function queryInsert(string $sql): int
    {
        $this->connection->query($sql);
        return $this->connection->insert_id;
    }

    public function queryUpdate(string $sql): bool
    {
        return $this->connection->query($sql);
    }

    public function queryDelete(string $sql): bool
    {
        return $this->connection->query($sql);
    }

    public function beginTransaction(): void
    {
        $this->connection->begin_transaction();
    }

    public function commit(): void
    {
        $this->connection->commit();
    }

    public function rollback(): void
    {
        $this->connection->rollback();
    }

    public function closeConnection(): void
    {
        if ($this->connection === null) {
            return;
        }

        $this->connection->close();
    }
}
