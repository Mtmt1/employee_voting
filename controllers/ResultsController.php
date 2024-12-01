<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Vote.php';

class ResultsController extends Controller {
    private $voteModel;
    
    public function __construct() {
        parent::__construct();
        $this->voteModel = new Vote($this->db);
    }
    
    public function index() {
        $categoryWinners = $this->voteModel->getCategoryWinners();
        $activeVoters = $this->voteModel->getActiveVoters();
        
        $this->render('results/index', [
            'categoryWinners' => $categoryWinners,
            'activeVoters' => $activeVoters
        ]);
    }
} 