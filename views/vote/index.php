<?php
require_once 'config/Database.php';
require_once 'models/Vote.php';

$database = new Database();
$db = $database->getConnection();

$stmt = $db->prepare("SELECT id, name FROM employees");
$stmt->execute();
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT id, name FROM vote_categories");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html data-bs-theme="dark">
<head>
    <title>Employee Appreciation Voting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1a1d20;
            color: #e9ecef;
        }
        
        .voting-container {
            background-color: #212529;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        .form-control, .form-select {
            background-color: #2b3035;
            border-color: #495057;
            color: #e9ecef;
        }

        .form-control:focus, .form-select:focus {
            background-color: #2b3035;
            border-color: #0d6efd;
            color: #e9ecef;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .btn-primary {
            padding: 0.8rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .page-header {
            color: #0d6efd;
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-label {
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .alert {
            border-radius: 10px;
        }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const voterSelect = document.getElementById('voter_id');
        const nomineeSelect = document.getElementById('nominee_id');

        function updateNomineeOptions() {
            const voterId = voterSelect.value;
            
            // Enable all options first
            Array.from(nomineeSelect.options).forEach(option => {
                option.disabled = false;
            });

            // Disable the voter's own option in nominee select
            if (voterId) {
                const nomineeOption = nomineeSelect.querySelector(`option[value="${voterId}"]`);
                if (nomineeOption) {
                    nomineeOption.disabled = true;
                }
                
                // If currently selected nominee is the same as voter, reset selection
                if (nomineeSelect.value === voterId) {
                    nomineeSelect.value = '';
                }
            }
        }

        voterSelect.addEventListener('change', updateNomineeOptions);
        updateNomineeOptions(); // Run once on page load
    });
    </script>
</head>
<body>
    <div class="container mt-5 mb-5">
        <ul class="nav nav-pills justify-content-center mb-4">
            <li class="nav-item">
                <a class="nav-link active" href="index.php">Submit Vote</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="results.php">View Results</a>
            </li>
        </ul>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> Your vote has been submitted successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if (isset($_GET['error']) && $_GET['error'] === 'self_nomination'): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> You cannot nominate yourself.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <div class="voting-container">
                    <h1 class="page-header">Employee Appreciation Voting</h1>
                    <form action="submit_vote.php" method="POST">
                        <div class="mb-4">
                            <label class="form-label">Voter</label>
                            <select name="voter_id" class="form-select" required id="voter_id">
                                <option value="">Select Your Name</option>
                                <?php foreach($employees as $employee): ?>
                                    <option value="<?php echo $employee['id']; ?>">
                                        <?php echo htmlspecialchars($employee['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Nominee</label>
                            <select name="nominee_id" class="form-select" required id="nominee_id">
                                <option value="">Select Employee</option>
                                <?php foreach($employees as $employee): ?>
                                    <option value="<?php echo $employee['id']; ?>">
                                        <?php echo htmlspecialchars($employee['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Select Category</option>
                                <?php foreach($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>">
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Comment</label>
                            <textarea name="comment" class="form-control" rows="4" required 
                                    placeholder="Write your appreciation message here..."></textarea>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">Submit Vote</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 