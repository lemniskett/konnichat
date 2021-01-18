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

function generateChatID() {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 6; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if(! $username = $_SESSION['username']) {
    echo json_encode(array('code' => 403, 'status' => 'Forbidden'));
    die();
}

$groupChatName  = $_POST['groupchatname'];
$prepare        = $mysql->prepare(
    'INSERT INTO tbGroupChat (id, name) VALUES (?, ?)'
);
$success        = FALSE;
$try            = 0;

while(! $success && $try <= 3) {
    $chatID     = generateChatID();
    $prepare->bind_param('ss', $chatID, $groupChatName);
    $success    = $prepare->execute();
    $try++;
}
$prepare->close();

if ($success) {
    $prepare    = $mysql->prepare(
        'INSERT INTO 
        tbGroupChatOwnership (id_groupchat, username_username, privilege) VALUES (?, ?, ?)'
    );
    $privilege  = 2;
    $prepare->bind_param('ssi', $chatID, $username, $privilege);
    reportResult($prepare->execute());
} else {
    reportResult(FALSE);
}
$prepare->close();