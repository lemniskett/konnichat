<?php
session_start();
include 'koneksi.php';

$username   = $_SESSION['username'];

$prepare    = $mysql->prepare(
    'SELECT username,fullname,status FROM tbUsername WHERE username=?'
);

$prepare->bind_param('s', $username);
$prepare->execute();
$prepare->bind_result($fetchedUsername, $fetchedFullname, $fetchedStatus);

if($prepare->fetch()){
    $arr = array(
        'username' => $fetchedUsername,
        'fullname' => $fetchedFullname,
        'status' => $fetchedStatus
    );
    $prepare->close();
    echo json_encode($arr);
}