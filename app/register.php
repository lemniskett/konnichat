<?php 
session_start(); 
include 'config.php';
if( $_SESSION['loggedin'] ) {
    header('location: .');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Konnichat!</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link href="assets/stylesheet/style-common.css" rel="stylesheet">
    <link href="assets/stylesheet/style-common-sw.css" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/imgs/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/imgs/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/imgs/favicon/favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">
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
                <h2 class="hc-center-text">Register to join us!</h2>
                <?php if($_GET['success'] == 'no') { ?>
                <div class="hc-bad-alert">Username is already taken</div>
                <?php } ?>
                <form class="hc-grid-col-1" method="POST" action="./functions/user_add.php">
                    <input class="hc-center-text hc-margin" type="text" name="hc-username" placeholder="Username" maxlength="24">
                    <input class="hc-center-text hc-margin" type="text" name="hc-fullname" placeholder="Full Name" maxlength="50">
                    <input class="hc-center-text hc-margin" type="password" name="hc-password" placeholder="Password">
                    <button class="hc-center-text hc-margin" type="submit">Register</button>
                </form>
                <p class="hc-small-font hc-center-text">Already registered? <a class="hc-link" href="login.php">Login here.</a></p>
            </div>
        </div>
    </div>
</body>
</html>