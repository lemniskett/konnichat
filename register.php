<?php 
session_start(); 

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
                <form class="hc-grid-col-1" method="POST" action="./functions/user_add.php">
                    <input class="hc-center-text hc-margin" type="text" name="hc-username" placeholder="Username">
                    <input class="hc-center-text hc-margin" type="text" name="hc-fullname" placeholder="Full Name">
                    <input class="hc-center-text hc-margin" type="password" name="hc-password" placeholder="Password">
                    <button class="hc-center-text hc-margin" type="submit">Register</button>
                </form>
                <p class="hc-small-font hc-center-text">Already registered? <a class="hc-link" href="login.php">Login here.</a></p>
            </div>
        </div>
    </div>
</body>
</html>