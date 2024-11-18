<?php
session_start();
include '../config/conn.php';
session_destroy();
header('Location: ../auth/Login.php');
exit();
?>