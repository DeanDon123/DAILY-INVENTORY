<?php
session_start();
require '../db.php';

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(135deg, #007bff 40%, #00d4ff);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
    font-family: "Segoe UI", sans-serif;
}

.login-box {
    background: white;
    padding: 40px 30px;
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    width: 100%;
    max-width: 380px;
}

h4 {
    font-weight: 600;
    color: #333;
}

.btn-primary {
    background: #007bff;
    border: none;
    font-weight: 500;
}

.btn-primary:hover {
    background: #0069d9;
}

.alert {
    font-size: 0.9rem;
}

@media (max-width: 576px) {
    .login-box {
        padding: 30px 20px;
        margin: 0 10px;
    }

    h4 {
        font-size: 1.2rem;
    }
}
</style>
</head>
<body>
<div class="login-box text-center">
    <h4 class="mb-3">🔐 Admin Login</h4>
    <?php if ($error): ?>
        <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3 text-start">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Enter your username" required>
        </div>
        <div class="mb-3 text-start">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
        </div>
        <button class="btn btn-primary w-100 py-2">Login</button>
    </form>
</div>
</body>
</html>
