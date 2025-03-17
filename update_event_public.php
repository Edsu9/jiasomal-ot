<?php
session_start();
require_once 'config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authorized']);
    exit();
}

// Check if required parameters are provided
if (!isset($_POST['event_id']) || !isset($_POST['public_display'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit();
}

$event_id = mysqli_real_escape_string($conn, $_POST['event_id']);
$public_display = (int)$_POST['public_display'];

// Update the event's public display status
$query = "UPDATE events SET public_display = $public_display, updated_at = NOW() WHERE id = $event_id";

if (mysqli_query($conn, $query)) {
    echo json_encode([
        'success' => true, 
        'message' => $public_display ? 'Event is now visible on public page' : 'Event hidden from public page'
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
}

