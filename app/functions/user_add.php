<?php

session_start();
include 'koneksi.php';
include '../config.php';
$username       = $_POST['hc-username'];
$fullname       = $_POST['hc-fullname'];
$password       = sha1($_POST['hc-password']);
$initialStatus  = "Hi! I'm a new user.";

$prepare    = $mysql->prepare(
                'INSERT INTO
                tbUsername (username, fullname, password, status)
                VALUES (?, ?, ?, ?)'
            );

$prepare->bind_param('ssss', $username, $fullname, $password, $initialStatus);
if($prepare->execute()) {
    header('location: ../login.php?from=register');
} else {
    header('location: ../register.php?success=no');
}
$prepare->close();