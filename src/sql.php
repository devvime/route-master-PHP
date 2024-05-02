<?php

class Sql {

    private $pdo;

    public function __construct($db_file) {
        try {
            $this->pdo = new PDO("sqlite:$db_file");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "SQLite connection error: " . $e->getMessage();
            exit();
        }
    }

    private function execute($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    public function list($table) {
        $query = "SELECT * FROM $table";
        $stmt = $this->execute($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($table, $id) {
        $query = "SELECT * FROM $table WHERE id = ?";
        $stmt = $this->execute($query, [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->execute($query, array_values($data));
        return $this->pdo->lastInsertId();
    }

    public function update($table, $id, $data) {
        $setClause = implode(', ', array_map(function($key) {
            return "$key = ?";
        }, array_keys($data)));

        $query = "UPDATE $table SET $setClause WHERE id = ?";
        $params = array_values($data);
        $params[] = $id;

        $this->execute($query, $params);
    }

    public function delete($table, $id) {
        $query = "DELETE FROM $table WHERE id = ?";
        $this->execute($query, [$id]);
    }
}