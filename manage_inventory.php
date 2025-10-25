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
<title>Manage Inventory</title>
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
        flex-wrap: wrap; /* makes navbar items wrap on smaller screens */
    }

    .navbar .btn {
        margin: 5px 3px;
    }

    .content-container {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 80vh;
        padding-top: 30px;
    }

    .card {
        border-radius: 15px;
        width: 100%;
        max-width: 700px;
    }

    table {
        width: 100%;
    }

    th, td {
        text-align: center;
        vertical-align: middle;
    }

    @media (max-width: 576px) {
        .navbar-brand {
            font-size: 1.1rem;
        }
        .card-body h5 {
            font-size: 1.2rem;
        }
    }
</style>
</head>
<body>

<!-- NAVIGATION -->
<nav class="navbar navbar-dark bg-dark px-3">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <span class="navbar-brand">📦 Inventory Management</span>
        <div>
            <a href="dashboard.php" class="btn btn-sm btn-light">Back</a>
            <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
        </div>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="container content-container">
    <div class="card shadow-lg border-0">
        <div class="card-body">
            <h5 class="mb-3 text-center">Inventory Records</h5>
            <p class="text-muted text-center mb-4">Here you can view and manage all inventory items.</p>

            <!-- Example Table -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Example static data, you can replace with PHP query results -->
                        <tr>
                            <td>1</td>
                            <td>Printer Ink</td>
                            <td>Office Supplies</td>
                            <td>25</td>
                            <td>
                                <button class="btn btn-sm btn-warning">Edit</button>
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Monitor</td>
                            <td>Hardware</td>
                            <td>10</td>
                            <td>
                                <button class="btn btn-sm btn-warning">Edit</button>
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Add New Item Button -->
            <div class="d-grid mt-4">
                <a href="#" class="btn btn-primary btn-lg">➕ Add New Item</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
