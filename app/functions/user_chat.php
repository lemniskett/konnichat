<?php
session_start();
include 'koneksi.php';
include '../config.php';
if(! $username = $_SESSION['username']) {
    echo json_encode(array('code' => 403, 'status' => 'Forbidden'));
    die();
}

$queryGroupChat =
    "SELECT 
    tbGroupChatOwnership.id_groupchat,
    tbGroupChat.name,
    tbGroupChatOwnership.privilege
    FROM tbGroupChatOwnership LEFT JOIN tbGroupChat
    ON tbGroupChatOwnership.id_groupchat = tbGroupChat.id
    WHERE username_username='$username'";

$rawGroupChat   = $mysql->query($queryGroupChat);
/*
$prepare->bind_param('s', $username);
$prepare->execute();
$prepare->bind_result($fetchedIdGroupChat, $fetchedGroupChatName, $fetchedPrivilege);
*/

$content = array();
while($groupChat = $rawGroupChat->fetch_assoc()) {
    $groupChatID    = $groupChat['id_groupchat'];
    $groupChatName  = $groupChat['name'];
    $privilege      = $groupChat['privilege'];

    $query          = 
        "SELECT 
        tbGroupChatMessage.id,
        tbGroupChatMessage.username_username,
        tbUsername.fullname,
        tbGroupChatMessage.message,
        tbGroupChatMessage.type,
        tbGroupChatMessage.sent_on
        FROM tbGroupChatMessage LEFT JOIN tbUsername
        ON tbGroupChatMessage.username_username = tbUsername.username
        WHERE tbGroupChatMessage.id_groupchat='$groupChatID'
        ORDER BY tbGroupChatMessage.id ASC";
    $rawMessage     = $mysql->query($query);
    $messageContent = array();
    while($message  = $rawMessage->fetch_assoc()) {
        $messageArr     = array(
            'id'        => $message['id'],
            'username'  => $message['username_username'],
            'fullname'  => $message['fullname'],
            'message'   => $message['message'],
            'type'      => $message['type'],
            'sentOn'    => $message['sent_on']
        );
        array_push($messageContent, $messageArr);
    }
    $rawMessage->close();
    $arr    = array(
        'groupChatID'   => $groupChatID,
        'groupChatName' => $groupChatName,
        'privilege'     => $privilege,
        'messages'      => $messageContent
    );
    array_push($content, $arr);
}
$rawGroupChat->close();
$data   = array(
    'code'      => 200,
    'status'    => 'OK',
    'content'   => $content
);

if($_POST['crc32'] == 'yes'){
    echo crc32(json_encode($data));
} else {
    echo json_encode($data);
}
