<?php
session_start();
header('Content-Type: application/json');

// Return the current progress
if (isset($_SESSION['progress'])) {
    echo json_encode(['progress' => $_SESSION['progress']]);
} else {
    echo json_encode(['progress' => 0]);  // Default if progress hasn't been set yet
}
?>
