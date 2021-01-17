<?php 
session_start();
if( ! $_SESSION['loggedin'] ) {
    header('location:login.php');
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
    <link href="index.css" rel="stylesheet">
    <script src="assets/js/main.js"></script>
    <script>
        const myUsername = "<?php echo $_SESSION['username']; ?>";
    </script>
</head>
<body>
    <div id="home-container">
        <div id="contacts">
            <div id="header" class="hc-center-everything">
                <h2>Konnichat!</h2>
                <div id="profile" onclick="chats.show('profile')">
                    <img id="profile-picture" src="https://github.com/lemniskett/host/raw/master/blank-pp.png">
                </div>
            </div>
            <!--
            <div id="profile" class="contact-profile" onclick="chats.show('profile')">
                <div class="hc-center-everything no-select">
                    <img class="contact-picture" src="https://github.com/lemniskett/host/raw/master/blank-pp.png">
                </div>
                <div class="hc-margin-left name-container">
                    <div class="hc-grid-col-1" style="height: fit-content">
                        <span class="contact-name" id="profile-fullname"></span>
                        <span class="contact-message" id="profile-status"></span>
                    </div>
                </div>
            </div>
            -->
            <div id="public-chat" class="contact-profile" onclick="chats.show('public-chat'); chats.triggerPublicMessages()">
                <div class="hc-center-everything no-select">
                    <img class="contact-picture" src="https://github.com/lemniskett/host/raw/master/blank-pp.png">
                </div>
                <div class="hc-margin-left name-container">
                    <div class="hc-grid-col-1" style="height: fit-content">
                        <span class="contact-name">Public Chat</span>
                        <span class="contact-message">Talk with people!</span>
                    </div>
                </div>
            </div>
        </div>
        <div id="chats" class="hc-center-everything">
            <div class="empty">
                <h1 class="hc-center-text">It's empty here...</h1>
                <p class="hc-center-text">Select a contact to get started. :)</p>
            </div>
            <div class="profile" style="width: calc(100% - 96px)">
                <div id="profile-container" class="hc-grid-col-1">
                    <h1 class="hc-center-text">Modify your profile</h1>
                    <label class="hc-margin">
                        Full name
                        <input id="input-fullname" class="higher hc-margin-top" name="hc-fullname" onchange="user.update('fullname')" autocomplete="off">
                    </label>
                    <label class="hc-margin">
                        Status
                        <input id="input-status" class="higher hc-margin-top" name="hc-status" onchange="user.update('status')" autocomplete="off">
                    </label>
                    <button onclick="changePass('open')" class="hc-margin">Update Password</button>
                    <a href="functions/logout.php" style="display: inherit;"><button class="hc-margin warn" onclick="">Log Out</button></a>
                </div>
            </div>
            <div class="public-chat">
                <div id="public-chat-container"></div>
            </div>
            <form class="hc-grid-col-2" id="chat-form">
                <textarea id="chat-input" autocomplete="off"></textarea>
                <button id="chat-submit" type="submit">Send</button>
            </form>
            <button id="button-back" onclick="chats.show('empty')">Back</button>
            <h3 id="chats-title"></h3>
        </div>
    </div>
    <div id="password-container" class="hide">
        <form class="hc-grid-col-1 hc-box higher" id="password-form">
            <h3 class="hc-center-text">Change your password.</h3>
            <label class="hc-margin">
                Current password
                <input type="password" id="input-current-pass" class="hc-margin-top evenhigher">
            </label>
            <label class="hc-margin">
                New password
                <input type="password" id="input-new-pass" class="hc-margin-top evenhigher">
            </label>
            <button type="submit" class="hc-margin">Change password</button>
            <button type="button" onclick="changePass('close')" class="hc-margin warn">Cancel</button>
        </form>
    </div>
    <script src="index.js"></script>
</body>
</html>