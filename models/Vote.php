<?php
class Vote {
    private $conn;
    private $table_name = "votes";

    public $id;
    public $voter_id;
    public $nominee_id;
    public $category_id;
    public $comment;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                (voter_id, nominee_id, category_id, comment)
                VALUES
                (:voter_id, :nominee_id, :category_id, :comment)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":voter_id", $this->voter_id);
        $stmt->bindParam(":nominee_id", $this->nominee_id);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":comment", $this->comment);

        return $stmt->execute();
    }
    
    public function getCategoryWinners() {
        return $this->conn->query("
            WITH RankedNominees AS (
                SELECT 
                    v.category_id,
                    v.nominee_id,
                    e.name as nominee_name,
                    vc.name as category_name,
                    COUNT(*) as vote_count,
                    ROW_NUMBER() OVER (PARTITION BY v.category_id ORDER BY COUNT(*) DESC) as rn
                FROM votes v
                JOIN employees e ON v.nominee_id = e.id
                JOIN vote_categories vc ON v.category_id = vc.id
                GROUP BY v.category_id, v.nominee_id, e.name, vc.name
            )
            SELECT *
            FROM RankedNominees
            WHERE rn = 1
            ORDER BY category_name
        ")->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getActiveVoters() {
        return $this->conn->query("
            SELECT 
                e.name,
                COUNT(*) as votes_cast
            FROM votes v
            JOIN employees e ON v.voter_id = e.id
            GROUP BY v.voter_id, e.name
            ORDER BY votes_cast DESC
            LIMIT 5
        ")->fetchAll(PDO::FETCH_ASSOC);
    }
} 