<?php
session_start();
include 'koneksi.php';

if(! $username = checkSession()) {
    echo json_encode(array('code' => 403, 'status' => 'Forbidden'));
    die();
}