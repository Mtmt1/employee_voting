<?php
class Category {
    private $conn;
    private $table_name = "vote_categories";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getAll() {
        $query = "SELECT id, name FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 