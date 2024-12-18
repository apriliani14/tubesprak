<?php
include_once 'db.php';
$data = $pdo->query("SELECT * FROM events")->fetchAll();

$events = [];
foreach ($data as $event) {
    $events[] = [
        'title' => $event['event_name'],
        'start' => $event['event_date'] . ' ' . $event['event_time'],
        'description' => $event['event_description'],
        'category' => $event['event_category'], 
        'time' => $event['event_time'], 
    ];
}

$events = json_encode($events);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender FullCalendar</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <?php include 'components/header.php' ?>

    <div class="container">
        <h1 class="text-center mb-4">Kalender dengan FullCalendar</h1>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addEventModal">Tambah Event</button>
        <div id="calendar"></div>
    </div>

    <!-- Modal untuk Detail Event -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Detail Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="eventTitle"></p>
                    <p id="eventDate"></p>
                    <p id="eventDescription"></p> 
                    <p id="eventCategory"></p> 
                    <p id="eventTime"></p> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Tambah Event -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventModalLabel">Tambah Event Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/add_event.php" id="addEventForm">
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

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: <?= $events ?>,
                eventClick: function (info) {
                    // Menampilkan modal dengan detail event
                    $('#eventTitle').text(info.event.title);
                    $('#eventDate').text(info.event.start.toISOString().slice(0, 10));
                    $('#eventDescription').text(info.event.extendedProps.description); 
                    $('#eventCategory').text(info.event.extendedProps.category); 
                    $('#eventTime').text(info.event.extendedProps.time); 
                    $('#eventModal').modal('show');
                }
            });

            calendar.render();
        });
    </script>
</body>

</html>
