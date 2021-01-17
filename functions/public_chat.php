<?php
include 'koneksi.php';

$prepare    = $mysql->prepare(
    'SELECT tbPublicChat.id,tbPublicChat.username_username,
    tbUsername.fullname,tbPublicChat.message 
    FROM tbPublicChat 
    LEFT JOIN tbUsername 
    ON tbPublicChat.username_username = tbUsername.username'
);

$prepare->execute();
$prepare->bind_result($fetchedId, $fetchedUsername, $fetchedFullname, $fetchedMessage);

$data = array();
while($prepare->fetch()){
    $message    = array(
        'id'        => $fetchedId,
        'username'  => $fetchedUsername,
        'fullname'   => $fetchedFullname,
        'message'    => $fetchedMessage
    );
    array_push($data, $message);
}
$prepare->close();
$jsonData   = json_encode($data);
if($_POST['crc32'] == 'yes'){
    echo crc32($jsonData);
} else {
    echo $jsonData;
}