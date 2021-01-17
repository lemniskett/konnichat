<?php
session_start();
include 'koneksi.php';

function reportResult($whatToReport) {
    if($whatToReport){
        echo json_encode(array('return' => 'success'));
        return TRUE;
    } else {
        echo json_encode(array('return' => 'failed'));
        return FALSE;
    }
}

$username   = $_SESSION['username'];
$message    = $_POST['message'];

if(trim($message) == ''){
    reportResult(FALSE);
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