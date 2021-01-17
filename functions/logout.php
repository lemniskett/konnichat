<?php
session_start();
$_SESSION['username']   = NULL;
$_SESSION['fullname']   = NULL;
$_SESSION['loggedin']   = FALSE;
header('location: ../login.php');