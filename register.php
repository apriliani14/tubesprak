<?php 
include 'db.php';
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token tidak valid.");
    }

    unset($_SESSION['csrf_token']); 

    // Ambil data dari form
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $admin_code = htmlspecialchars(trim($_POST['admin_code'])); // Input kode admin

    // Validasi input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Email tidak valid.");
    }

    if ($password !== $confirm_password) {
        die("Password dan konfirmasi password tidak sama.");
    }

    if (strlen($password) < 8) {
        die("Password harus minimal 8 karakter.");
    }

    // Tentukan role berdasarkan kode admin
    $role = 'user'; 
    if ($admin_code === '@DM!N_2024') { 
        $role = 'admin';
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Simpan data ke database
    $stmt = $pdo->prepare("INSERT INTO Users (name, email, password, role) VALUES (?, ?, ?, ?)");
    try {
        if ($stmt->execute([$name, $email, $hashed_password, $role])) {
            echo "<script>alert('Registrasi berhasil!'); window.location.href = 'index.php';</script>";
            exit;
        } else {
            die("Gagal menyimpan data ke database.");
        }
    } catch (PDOException $e) {
        die("Terjadi kesalahan: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Jadwal Kegiatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 1rem 0;
            text-align: center;
        }
        .register-container {
            margin-top: 5rem;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .form-control:focus {
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            border-color: #007bff;
        }
        .footer-text {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <header>
        <h1>Jadwal Kegiatan</h1>
    </header>
    <div class="container register-container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Daftar Akun</h2>
                        <form method="POST" action="">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" minlength="8" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="admin_code" class="form-label">Kode Admin (Opsional)</label>
                                <input type="text" name="admin_code" id="admin_code" class="form-control" placeholder="Masukkan kode admin jika ada">
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary w-100">Daftar</button>
                            </div>
                            <p class="text-center">Sudah punya akun? <a href="index.php">Login</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="text-center footer-text mt-5">
        <small>&copy; 2024 - Jadwal Kegiatan</small>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
