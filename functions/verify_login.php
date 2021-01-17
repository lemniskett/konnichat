<?php
session_start();
include 'koneksi.php';

$username   = $_POST['hc-username'];
$password   = sha1($_POST['hc-password']);

$prepare    = $mysql->prepare(
                        'SELECT username,fullname 
                        FROM tbUsername 
                        WHERE username=? 
                        AND password=?'
                    );

$prepare->bind_param('ss', $username, $password);
$prepare->execute();
$prepare->bind_result($fetched_username, $fetched_fullname);

if($prepare->fetch()) {
    $_SESSION['username']   = $fetched_username;
    $_SESSION['fullname']   = $fetched_fullname;
    $_SESSION['loggedin']   = TRUE;
    $prepare->close();
    header('location: ../');
} else {
    $prepare->close();
    header('location: ../login.php?success=no');
}