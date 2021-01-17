<?php 
session_start(); 

if($_SESSION['loggedin']) {
    header('location: .');
}

if($_GET['from'] == 'register') {
    echo '<script>alert("Register success")</script>';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Konnichat!</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="description" content="Simple open-source chat app.">
    <link href="assets/stylesheet/style-common.css" rel="stylesheet">
    <link href="assets/stylesheet/style-common-sw.css" rel="stylesheet">
    <style>
        #loginform-container {
            width: 60%;
        }

        @media(max-width: 768px){
            #login-container {
                grid-template-columns: auto;
                grid-template-rows: 0 100%;
            }

            #loginform-container {
                width: 80%;
            }
        }
    </style>
    <script src="assets/js/main.js"></script>
</head>
<body>
    <div class="hc-grid-col-2 hc-occupy-space" id="login-container">
        <div class="hc-center-everything"></div>
        <div class="hc-center-everything">
            <div class="hc-box" id="loginform-container">
                <h2 class="hc-center-text">Login page.</h2>
                <form class="hc-grid-col-1" method="POST" action="./functions/verify_login.php">
                    <input class="hc-center-text hc-margin" type="text" name="hc-username" placeholder="Username">
                    <input class="hc-center-text hc-margin" type="password" name="hc-password" placeholder="Password">
                    <button class="hc-center-text hc-margin" type="submit">Login</button>
                </form>
                <p class="hc-small-font hc-center-text">Not yet registered? <a class="hc-link" href="register.php">Register here.</a></p>
            </div>
        </div>
    </div>
</body>
</html>