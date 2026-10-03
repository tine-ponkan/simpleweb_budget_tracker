<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $amount = $_POST['amount'];
    $type = $_POST['type'];
    $category = trim($_POST['category']);
    $date = $_POST['transaction_date'];

    if (!empty($title) && !empty($amount) && !empty($type) && !empty($date)) {
        $stmt = $pdo->prepare("INSERT INTO transactions (title, amount, type, category, transaction_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $amount, $type, $category, $date]);
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Add New Transaction</h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Title / Description</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g., Groceries, Allowance" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Amount (₱)</label>
                        <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" required>
                            <option value="expense">Expense</option>
                            <option value="income">Income</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" placeholder="e.g., Food, Transport, Bills" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="transaction_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Transaction</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>