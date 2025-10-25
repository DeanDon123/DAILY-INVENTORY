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
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background-color: #f8f9fa;
    }

    .navbar-brand {
        font-weight: 600;
        font-size: 1.25rem;
    }

    .navbar {
        flex-wrap: wrap;
    }

    .navbar .btn {
        margin: 5px 3px;
    }

    .dashboard-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        text-align: center;
    }

    .card {
        border-radius: 15px;
        max-width: 500px;
        width: 100%;
    }

    .btn-module {
        font-size: 1rem;
        font-weight: 500;
    }

    @media (max-width: 576px) {
        .navbar-brand {
            font-size: 1.1rem;
        }

        .card-body h4 {
            font-size: 1.2rem;
        }

        .btn-module {
            font-size: 0.95rem;
        }
    }
</style>
</head>
<body>

<!-- NAVIGATION -->
<nav class="navbar navbar-dark bg-dark px-3">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <span class="navbar-brand">🛠️ Admin Dashboard</span>
        <div class="d-flex flex-wrap justify-content-end">
            <a href="manage_inventory.php" class="btn btn-sm btn-light">Manage Inventory</a>
            <a href="logout.php" class="btn btn-sm btn-danger ms-1">Logout</a>
        </div>
    </div>
</nav>

<!-- MAIN DASHBOARD -->
<div class="container dashboard-container">
    <div class="card shadow-lg border-0">
        <div class="card-body">
            <h4 class="mb-3">Welcome, <?= htmlspecialchars($_SESSION['admin_username']) ?> 👋</h4>
            <p class="text-muted mb-4">Select a module below to manage.</p>
            
            <h6 class="text-muted mb-3">🏢 Branch Modules</h6>
            <div class="d-grid gap-2">
                <a href="malvar.php" class="btn btn-outline-primary btn-module">🏭 Malvar Branch</a>
                <a href="pagsanjan.php" class="btn btn-outline-success btn-module">🌿 Pagsanjan Branch</a>
                <a href="quezon.php" class="btn btn-outline-info btn-module">🏝️ Quezon Branch</a>
            </div>

            <hr class="my-4">
            <a href="logout.php" class="btn btn-outline-danger w-100 btn-module">🚪 Logout</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
