<?php
session_start();
include 'koneksi.php';
include '../config.php';
function reportResult($whatToReport) {
    if($whatToReport){
        echo json_encode(array('code' => 200, 'status' => 'OK'));
        return TRUE;
    } else {
        echo json_encode(array('code' => 500, 'status' => 'Internal Server Error'));
        return FALSE;
    }
}

function checkPrivilege($usernameToCheck, $groupChatToCheck) {
    global $mysql;
    $query      = 
        "SELECT privilege 
        FROM tbGroupChatOwnership 
        WHERE username_username='$usernameToCheck' 
        AND id_groupchat='$groupChatToCheck'";
    $rawData    = $mysql->query($query);
    $data       = $rawData->fetch_assoc();
    $return     = $data['privilege'];
    $rawData->close();
    return $return;
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

if(substr($message, 0, 1) == '!') {
    $command    = explode(' ', $message.' ');
    switch($command[0]) {
        case '!add':
            $privilege  = checkPrivilege($username, $groupChat);
            if($privilege == 0){
                echo json_encode(array('code' => 403, 'status' => 'Forbidden'));
                break;
            }
            $i  = 1;
            while($newMember = $command[$i]) {
                $isMemberExists = checkPrivilege($newMember, $groupChat);
                if(! isset($isMemberExists)) {
                    $prepare        = $mysql->prepare(
                        'INSERT INTO
                        tbGroupChatOwnership(id_groupchat, username_username, privilege)
                        VALUES(?, ?, ?)'
                    );
                    $defPrivilege  = 0;
                    $prepare->bind_param('ssi', $groupChat, $newMember, $defPrivilege);
                    $prepare->execute();
                    $prepare->close();
                } 
                $i++;
            }
            reportResult(TRUE);
            break;
        case '!del':
            if(checkPrivilege($username, $groupChat) == 0){
                echo json_encode(array('code' => 403, 'status' => 'Forbidden'));
                break;
            }
            $i = 1;
            while($newMember = $command[$i]) {
                $prepare    = $mysql->prepare(
                    'DELETE FROM tbGroupChatOwnership
                    WHERE id_groupchat=? AND username_username=?'
                );
                $privilege  = 0;
                $prepare->bind_param('ss', $groupChat, $newMember);
                $prepare->execute();
                $prepare->close();
                $i++;
            }
            reportResult(TRUE);
            break;
        default:
            echo json_encode(array('code' => 400, 'status' => 'Bad Request'));
            break;
    }
} else {
    $prepare    = $mysql->prepare(
        'SELECT id_groupchat FROM tbGroupChatOwnership WHERE id_groupchat=? AND username_username=?'
    );
    $prepare->bind_param('ss', $groupChat, $username);
    $prepare->execute();
    $prepare->bind_result($fetchedID);
    if($prepare->fetch()) {
        $prepare->close();
        $prepare    = $mysql->prepare(
            'INSERT INTO 
            tbGroupChatMessage(id_groupchat, username_username, message, type)
            VALUES(?, ?, ?, ?)'
        );
        $type   = 'text';
        $prepare->bind_param('ssss', $groupChat, $username, $message, $type);
        reportResult($prepare->execute());
        $prepare->close();
    } else {
        echo json_encode(array('code' => 403, 'status' => 'Forbidden'));
        $prepare->close();
    }
}
