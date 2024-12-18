<?php
include 'db.php';
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $event_name = $_POST['event_name'];
    $event_description = $_POST['event_description'];
    $event_date = $_POST['event_date']; 
    $event_time = $_POST['event_time']; 
    $event_category = $_POST['event_category'];

    
    if (empty($event_name) || empty($event_description) || empty($event_date) || empty($event_time) || empty($event_category)) {
        $error_message = "Semua field harus diisi!";
    } else {
        
        $stmt = $pdo->prepare("INSERT INTO Events (user_id, event_name, event_description, event_date, event_time, event_category) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$user_id, $event_name, $event_description, $event_date, $event_time, $event_category])) {
            
            header("Location: dashboard.php");
            exit;
        } else {
            $error_message = "Gagal menyimpan kegiatan ke database.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kegiatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
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
    </style>
    <link rel="stylesheet" href="assets/style.css">

</head>
<body>
     <?php include 'components/header.php' ?>
    <div class="container form-container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Tambah Kegiatan Baru</h2>
                        <?php if (isset($error_message)) { ?>
                            <div class="alert alert-danger">
                                <?php echo $error_message; ?>
                            </div>
                        <?php } ?>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="event_name" class="form-label">Nama Kegiatan</label>
                                <input type="text" name="event_name" id="event_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="event_description" class="form-label">Deskripsi Kegiatan</label>
                                <textarea name="event_description" id="event_description" class="form-control" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="event_date" class="form-label">Tanggal Kegiatan</label>
                                <input type="date" name="event_date" id="event_date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="event_time" class="form-label">Waktu Kegiatan</label>
                                <input type="time" name="event_time" id="event_time" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="event_category" class="form-label">Kategori Kegiatan</label>
                                <select name="event_category" id="event_category" class="form-control" required>
                                    <option value="Rapat">Rapat</option>
                                    <option value="Seminar">Seminar</option>
                                    <option value="Liburan">Liburan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary w-100">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
