<?php

include 'db.php';


session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Contoh untuk Pendaftaran
    if (isset($_POST['register'])) {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password

        try {
            // Query untuk menyimpan data pengguna
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $password]);
            $_SESSION['success'] = "Pendaftaran berhasil!";
            header("Location: login.php");
        } catch (PDOException $e) {
            $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
        }
    }

    // Contoh untuk Login
    elseif (isset($_POST['login'])) {
        $email = htmlspecialchars($_POST['email']);
        $password = $_POST['password'];

        try {
            // Query untuk mengambil data pengguna
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: dashboard.php");
            } else {
                $_SESSION['error'] = "Email atau password salah!";
                header("Location: login.php");
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
        }
    }

    // Contoh untuk Menambah Kegiatan
    elseif (isset($_POST['add_event'])) {
        $user_id = $_SESSION['user_id'];
        $event_name = htmlspecialchars($_POST['event_name']);
        $event_description = htmlspecialchars($_POST['event_description']);
        $event_date = $_POST['event_date'];
        $event_time = $_POST['event_time'];

        try {
            // Query untuk menyimpan kegiatan
            $stmt = $pdo->prepare("INSERT INTO events (user_id, event_name, event_description, event_date, event_time) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $event_name, $event_description, $event_date, $event_time]);
            $_SESSION['success'] = "Kegiatan berhasil ditambahkan!";
            header("Location: dashboard.php");
        } catch (PDOException $e) {
            $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}
?>
