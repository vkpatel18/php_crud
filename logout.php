<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: list.php");
        exit;
    }
    session_unset();
    session_destroy();
    header("location: login.php");
?>