<?php
session_start();
require '../db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quezon Branch</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand">🏝️ Quezon Branch</span>
    <a href="dashboard.php" class="btn btn-sm btn-light">Back</a>
</nav>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5>Welcome to Quezon Module</h5>
            <p>Manage records or assets related to the Quezon branch here.</p>
        </div>
    </div>
</div>
</body>
</html>
