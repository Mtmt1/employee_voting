<?php
require_once __DIR__ . '/../config/Database.php';

class Controller {
    protected $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    protected function render($view, $data = []) {
        extract($data);
        require_once __DIR__ . "/../views/$view.php";
    }
} 