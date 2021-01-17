<?php
session_start();
include 'koneksi.php';

if(! $username = $_SESSION['username']) {
    echo json_encode(array('code' => 403, 'status' => 'Forbidden'));
    die();
}

$prepare    = $mysql->prepare(
    'SELECT username,fullname,status FROM tbUsername WHERE username=?'
);

$prepare->bind_param('s', $username);
$prepare->execute();
$prepare->bind_result($fetchedUsername, $fetchedFullname, $fetchedStatus);

if($prepare->fetch()){
    $arr = array(
        'code'      => 200,
        'status'    => 'OK',
        'username'  => $fetchedUsername,
        'fullname'  => $fetchedFullname,
        'status'    => $fetchedStatus
    );
    echo json_encode($arr);
} else {
    $arr = array(
        'code'      => 500,
        'status'    => 'Internal Server Error'
    );
    echo json_encode($arr);
}
$prepare->close();