<?php
require_once './backend/session_init.php';
echo "<pre>";
echo json_encode($_SESSION, JSON_PRETTY_PRINT);
echo "</pre>";
?>