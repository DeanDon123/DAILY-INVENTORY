<?php
session_start();
require '../db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// ✅ Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM coh_records WHERE id = ?");
    $stmt->execute([$delete_id]);
    $successMsg = "🗑️ Record deleted successfully!";
}

// ✅ Filters
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

// ✅ Base SQL
$sql = "SELECT * FROM coh_records WHERE branch = 'Malvar'";
$params = [];

// ✅ Add date filter if selected
if (!empty($from) && !empty($to)) {
    $sql .= " AND date BETWEEN :from AND :to";
    $params[':from'] = $from;
    $params[':to'] = $to;
}

$sql .= " ORDER BY date DESC";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Malvar Branch</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    * { box-sizing: border-box; font-family: "Segoe UI", Roboto, Arial, sans-serif; }
    body { background-color: #f8f9fa; }
    .navbar-brand { font-weight: 600; }
    .container { max-width: 900px; margin-top: 30px; }
    .card { border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    h2 {
        text-align: center; background: #4caf50; color: white;
        padding: 10px; border-radius: 8px; font-size: 18px; margin-bottom: 15px;
    }
    .alert {
        background: #e8f5e9; border: 1px solid #a5d6a7; color: #2e7d32;
        padding: 10px; border-radius: 6px; margin-bottom: 10px; text-align: center;
    }
    th { background-color: #4caf50; color: white; }
    td, th { text-align: center; padding: 8px; vertical-align: middle; }
    tr:hover { background-color: #f1f1f1; }
    .total {
        text-align: right; font-weight: bold; margin-top: 10px;
        background: #e3f2fd; border: 1px solid #90caf9;
        border-radius: 6px; padding: 8px;
    }
    .btn-back {
        display: inline-block; width: 100%; padding: 10px;
        background: #2196f3; color: white; border: none;
        border-radius: 6px; font-weight: 600; text-align: center;
        text-decoration: none; margin-top: 15px; transition: background 0.3s;
    }
    .btn-back:hover { background: #1976d2; }
    .btn-delete {
        background: #e53935; color: white; border: none;
        padding: 5px 10px; border-radius: 6px; font-size: 13px;
        cursor: pointer; transition: background 0.3s;
    }
    .btn-delete:hover { background: #c62828; }
    @media (max-width: 576px) {
        .date-filter { flex-direction: column; gap: 10px; }
    }
</style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand">🏭 Malvar Branch</span>
    <a href="dashboard.php" class="btn btn-sm btn-light">Back</a>
</nav>

<div class="container">
    <div class="card p-3">
        <h2>TOTAL COH RECORDS</h2>

        <?php if (!empty($successMsg)): ?>
            <div class="alert"><?= htmlspecialchars($successMsg) ?></div>
        <?php endif; ?>

        <!-- ✅ Date Filter -->
        <form method="get" class="mb-3">
            <div class="d-flex date-filter justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex flex-column">
                    <label for="from" class="fw-semibold">From:</label>
                    <input type="date" name="from" id="from" class="form-control" value="<?= htmlspecialchars($from) ?>">
                </div>
                <div class="d-flex flex-column">
                    <label for="to" class="fw-semibold">To:</label>
                    <input type="date" name="to" id="to" class="form-control" value="<?= htmlspecialchars($to) ?>">
                </div>
                <div class="d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">Filter</button>
                </div>
            </div>
        </form>

        <?php if (count($records) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Amount (₱)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        foreach ($records as $index => $r):
                            $total += $r['amount'];
                        ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($r['date']) ?></td>
                            <td><?= number_format($r['amount'], 2) ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');" style="display:inline;">
                                    <input type="hidden" name="delete_id" value="<?= $r['id'] ?>">
                                    <button type="submit" class="btn-delete">🗑 Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="total">
                Total (Filtered): ₱<?= number_format($total, 2) ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">No COH records found for Malvar branch.</div>
        <?php endif; ?>

        <a href="dashboard.php" class="btn-back">⬅ BACK TO DASHBOARD</a>
    </div>
</div>

</body>
</html>
