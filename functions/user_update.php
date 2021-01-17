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

switch($_POST['type']) {
    case 'status':
        $username   = $_SESSION['username'];
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
        $username       = $_SESSION['username'];
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
        $username       = $_SESSION['username'];
        $currentPass    = sha1($_POST['current-pass']);
        $newPass        = sha1($_POST['new-pass']);
        $prepare        = $mysql->prepare(
            'SELECT password FROM tbUsername
            WHERE username=?'
        );
        
        if($currentPass == $newPass) {
            reportResult(FALSE);
            return FALSE;
        }

        $prepare->bind_param('s', $username);
        $prepare->execute();
        $prepare->bind_result($fetchedPassword);
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
                reportResult(FALSE);
                $prepare->close();
                return FALSE;
            }
        } else {
            reportResult(FALSE);
            $prepare->close();
            return FALSE;
        }
}