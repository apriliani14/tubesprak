<?php 
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Ambil input dari form
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);

    // Validasi input
    if (empty($email) || empty($password)) {
        $error = "Email dan Password harus diisi.";
    } else {
        // Cek kredensial di database
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Login berhasil, simpan data pengguna ke session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            header("Location: dashboard.php");
            exit;
        } else {
            
            $error = "Email atau Password salah.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Jadwal Kegiatan</title>
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
        .login-container {
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
        <h1>PENCATATAN DAN PENJADWALAN</h1>
    </header>
    <div class="container login-container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Login</h2>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <form action="" method="POST">
                            <div class="mb-3">
                                <input type="text" placeholder="Email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" placeholder="Password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary w-100" name="submit">Login</button>
                            </div>
                            <p class="text-center">Anda belum punya akun? <a href="register.php">Register</a></p>
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
