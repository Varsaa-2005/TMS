<?php
// Start session only if not started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// include database connection
require_once __DIR__ . '/../db_connect.php';

?>
