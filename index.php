<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';

// Fetch summary totals
$totalIncome = $pdo->query("SELECT SUM(amount) FROM transactions WHERE type='income'")->fetchColumn() ?: 0;
$totalExpense = $pdo->query("SELECT SUM(amount) FROM transactions WHERE type='expense'")->fetchColumn() ?: 0;
$balance = $totalIncome - $totalExpense;

// Fetch all transactions
$stmt = $pdo->query("SELECT * FROM transactions ORDER BY transaction_date DESC, id DESC");
$transactions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary"><i class="bi bi-wallet2"></i> Budget Tracker</h2>
        <a href="add.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Transaction</a>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white p-3 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">Total Income</h6>
                    <h3 class="fw-bold">₱<?= number_format($totalIncome, 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white p-3 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">Total Expense</h6>
                    <h3 class="fw-bold">₱<?= number_format($totalExpense, 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card <?= $balance >= 0 ? 'bg-primary' : 'bg-dark' ?> text-white p-3 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">Current Balance</h6>
                    <h3 class="fw-bold">₱<?= number_format($balance, 2) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction List Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Recent Transactions</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($transactions)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No transactions found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($transactions as $t): ?>
                                <tr>
                                    <td><?= htmlspecialchars($t['transaction_date']) ?></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($t['title']) ?></td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($t['category']) ?></span></td>
                                    <td>
                                        <span class="badge <?= $t['type'] === 'income' ? 'bg-success' : 'bg-danger' ?>">
                                            <?= ucfirst($t['type']) ?>
                                        </span>
                                    </td>
                                    <td class="fw-bold <?= $t['type'] === 'income' ? 'text-success' : 'text-danger' ?>">
                                        <?= $t['type'] === 'income' ? '+' : '-' ?>₱<?= number_format($t['amount'], 2) ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-warning me-1"><i class="bi bi-pencil"></i></a>
                                        <a href="delete.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this transaction?')"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>