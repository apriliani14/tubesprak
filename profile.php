<?php
session_start();
require 'db.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "Pengguna tidak ditemukan!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .profile-container {
            margin-top: 3rem;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .btn-logout {
            background-color: #dc3545;
            border: none;
        }
        .btn-logout:hover {
            background-color: #c82333;
        }
    </style>
    <link rel="stylesheet" href="assets/style.css">

</head>
<body>
       <?php include 'components/header.php' ?>

    <div class="container profile-container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h2 class="card-title">Halo, <?= htmlspecialchars($user['name']); ?>!</h2>
                        <p class="text-muted">Berikut adalah informasi profil Anda:</p>
                        <div class="mt-4">
                            <p><strong>Nama:</strong> <?= htmlspecialchars($user['name']); ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
                        </div>
                        <a href="logout.php" class="btn btn-logout text-white mt-3">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="text-center mt-5 text-muted">
        <small>&copy; 2024 - Aplikasi Pengguna</small>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
