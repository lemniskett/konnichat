<?php
session_start();
include 'koneksi.php';

if(! $username = $_SESSION['username']) {
    echo json_encode(array('code' => 403, 'status' => 'Forbidden'));
    die();
}

$prepare    = $mysql->prepare(
    'SELECT tbPublicChat.id,tbPublicChat.username_username,
    tbUsername.fullname,tbPublicChat.message 
    FROM tbPublicChat 
    LEFT JOIN tbUsername 
    ON tbPublicChat.username_username = tbUsername.username'
);
$success = $prepare->execute();
$prepare->bind_result($fetchedId, $fetchedUsername, $fetchedFullname, $fetchedMessage);

$content = array();
while($prepare->fetch()){
    $message    = array(
        'id'        => $fetchedId,
        'username'  => $fetchedUsername,
        'fullname'  => $fetchedFullname,
        'message'   => $fetchedMessage
    );
    array_push($content, $message);
}
if($success){
    $data = array(
        'code'      => 200,
        'status'    => 'OK',
        'content'   => $content
    );
} else {
    $data = array(
        'code'      => 500,
        'status'    => 'Internal Server Error',
    );
}

$jsonData   = json_encode($data);
if($_POST['crc32'] == 'yes'){
    echo crc32($jsonData);
} else {
    echo $jsonData;
}