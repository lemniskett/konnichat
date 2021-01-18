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

switch($_POST['type']) {
    case 'status':
        $status     = $_POST['status'];
        $prepare    =  $mysql->prepare(
            'UPDATE tbUsername
            SET status=?
            WHERE username=?'
        );
        $prepare->bind_param('ss', $status, $username);
        reportResult($prepare->execute());
        $prepare->close();
    break;
    case 'fullname':
        $newFullname    = $_POST['fullname'];
        $prepare        = $mysql->prepare(
            'UPDATE tbUsername
            SET fullname=?
            WHERE username=?'
        );
        $prepare->bind_param('ss', $newFullname, $username);
        reportResult($prepare->execute());
        $prepare->close();
    break;
    case 'password':
        $currentPass    = sha1($_POST['currentpass']);
        $newPass        = sha1($_POST['newpass']);
        $prepare        = $mysql->prepare(
            'SELECT password FROM tbUsername
            WHERE username=?'
        );
        
        if($currentPass == $newPass) {
            echo json_encode(array('code' => 400, 'status' => 'Bad Request'));
            return FALSE;
        }

        $prepare->bind_param('s', $username);
        $prepare->execute();
        $prepare->bind_result($fetchedPassword);
        error_reporting(E_ALL ^ E_WARNING); 
        if($prepare->fetch()){
            $prepare->close();
            if($currentPass == $fetchedPassword){
                $prepare    = $mysql->prepare(
                    'UPDATE tbUsername
                    SET password=?
                    WHERE username=?'
                );
                $prepare->bind_param('ss', $newPass, $username);
                reportResult($prepare->execute());
                $prepare->close();
            } else {
                echo json_encode(array('code' => 400, 'status' => 'Bad Request'));
                $prepare->close();
                return FALSE;
            }
        } else {
            echo json_encode(array('code' => 400, 'status' => 'Bad Request'));
            $prepare->close();
            return FALSE;
        }
}