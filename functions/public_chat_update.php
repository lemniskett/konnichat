<?php
session_start();
include 'koneksi.php';

function reportResult($whatToReport) {
    if($whatToReport){
        echo json_encode(array('code' => 200, 'status' => 'OK'));
        return TRUE;
    } else {
        echo json_encode(array('code' => 500, 'status' => 'Internal Server Error'));
        return FALSE;
    }
}

$username   = $_SESSION['username'];
$message    = $_POST['message'];

if(trim($message) == ''){
    echo json_encode(array('code' => 400, 'status' => 'Bad Request'));
    die();
}

$prepare    = $mysql->prepare(
    'INSERT INTO 
    tbPublicChat(username_username, message)
    VALUES(?, ?)'
);

$prepare->bind_param('ss', $username, $message);
reportResult($prepare->execute());
$prepare->close();