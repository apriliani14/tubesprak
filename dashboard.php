<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$query = $pdo->prepare("SELECT * FROM Events WHERE user_id = ?");
$query->execute([$user_id]);
$events = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <?php include 'components/header.php' ?>
    <div class="container">
        <h2>Kegiatan Saya</h2>
        <ul class="event-list">
            <?php if (empty($events)): ?>
                <li>Tidak ada kegiatan yang ditemukan.</li>
            <?php else: ?>
                <?php foreach ($events as $event): ?>
                  <div class="card">
                    <div class="card-body">
                          <li>
                        <strong><?= htmlspecialchars($event['event_name']) ?></strong><br>
                        Tanggal: <?= htmlspecialchars($event['event_date']) ?>
                    </li>
                    <form action="/process/deleteEvent.php" method="post">
                        <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                    </div>
                  </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
    <footer class="footer-text">
        <small>&copy; 2024 - Sistem Kegiatan</small>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>