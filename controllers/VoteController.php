<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Vote.php';
require_once __DIR__ . '/../models/Employee.php';
require_once __DIR__ . '/../models/Category.php';

class VoteController extends Controller {
    private $voteModel;
    private $employeeModel;
    private $categoryModel;
    
    public function __construct() {
        parent::__construct();
        $this->voteModel = new Vote($this->db);
        $this->employeeModel = new Employee($this->db);
        $this->categoryModel = new Category($this->db);
    }
    
    public function index() {
        $employees = $this->employeeModel->getAll();
        $categories = $this->categoryModel->getAll();
        
        $this->render('vote/index', [
            'employees' => $employees,
            'categories' => $categories
        ]);
    }
    
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
            exit;
        }
        
        $voter_id = $_POST['voter_id'] ?? null;
        $nominee_id = $_POST['nominee_id'] ?? null;
        $category_id = $_POST['category_id'] ?? null;
        $comment = $_POST['comment'] ?? null;
        
        if (!$voter_id || !$nominee_id || !$category_id || !$comment) {
            die('All fields are required');
        }
        
        if ($voter_id === $nominee_id) {
            header('Location: index.php?error=self_nomination');
            exit;
        }
        
        try {
            $this->voteModel->voter_id = $voter_id;
            $this->voteModel->nominee_id = $nominee_id;
            $this->voteModel->category_id = $category_id;
            $this->voteModel->comment = $comment;
            
            $this->voteModel->create();
            header('Location: index.php?success=1');
            exit;
        } catch (Exception $e) {
            die('Error submitting vote: ' . $e->getMessage());
        }
    }
} 