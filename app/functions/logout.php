<?php
session_start();
include '../config.php';
$_SESSION['username']   = NULL;
$_SESSION['fullname']   = NULL;
$_SESSION['loggedin']   = FALSE;
header('location: ../login.php');