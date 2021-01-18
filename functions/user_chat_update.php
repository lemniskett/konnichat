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

if(! $username = $_SESSION['username']) {
    echo json_encode(array('code' => 403, 'status' => 'Forbidden'));
    die();
}

$message    = $_POST['message'];
$groupChat  = $_POST['groupchat'];

if(trim($message) == ''){
    echo json_encode(array('code' => 400, 'status' => 'Bad Request'));
    die();
}

$prepare    = $mysql->prepare(
    'INSERT INTO 
    tbGroupChatMessage(id_groupchat, username_username, message)
    VALUES(?, ?, ?)'
);

$prepare->bind_param('sss', $groupChat, $username, $message);
reportResult($prepare->execute());
$prepare->close();