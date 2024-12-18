<?php

require_once '../db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id = $_POST['event_id'];

    $sql = "DELETE FROM events WHERE id='$id'";

    $pdo->query($sql);

    header('Location: /dashboard.php', true, $permanent ? 301 : 302);

    exit();
}