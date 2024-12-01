<?php
require_once 'config/Database.php';
require_once 'models/Vote.php';

$database = new Database();
$db = $database->getConnection();

$categoryWinners = $db->query("
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

$activeVoters = $db->query("
    SELECT 
        e.name,
        COUNT(*) as votes_cast
    FROM votes v
    JOIN employees e ON v.voter_id = e.id
    GROUP BY v.voter_id, e.name
    ORDER BY votes_cast DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html data-bs-theme="dark">
<head>
    <title>Voting Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1a1d20;
            color: #e9ecef;
        }
        
        .results-container {
            background-color: #212529;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            margin-bottom: 2rem;
        }

        .page-header {
            color: #0d6efd;
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
        }

        .category-winner {
            background-color: #2b3035;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .category-name {
            color: #0d6efd;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .winner-name {
            font-size: 1.2rem;
            font-weight: 500;
        }

        .vote-count {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .nav-pills {
            margin-bottom: 2rem;
        }

        .table {
            background-color: #2b3035;
            border-radius: 10px;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <ul class="nav nav-pills justify-content-center mb-4">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Submit Vote</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="results.php">View Results</a>
            </li>
        </ul>

        <div class="results-container">
            <h1 class="page-header">Category Winners</h1>
            
            <?php foreach ($categoryWinners as $winner): ?>
                <div class="category-winner">
                    <div class="category-name"><?php echo htmlspecialchars($winner['category_name']); ?></div>
                    <div class="winner-name"><?php echo htmlspecialchars($winner['nominee_name']); ?></div>
                    <div class="vote-count"><?php echo $winner['vote_count']; ?> votes</div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="results-container">
            <h2 class="page-header">Most Active Voters</h2>
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Votes Cast</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activeVoters as $voter): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($voter['name']); ?></td>
                            <td><?php echo $voter['votes_cast']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 