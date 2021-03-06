<?php 
session_start();
include 'config.php';
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
    <link rel="apple-touch-icon" sizes="180x180" href="assets/imgs/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/imgs/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/imgs/favicon/favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">
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
                    <img id="profile-picture" class="no-select" src="https://github.com/lemniskett/host/raw/master/blank-pp.png">
                </div>
                <button id="menu" onclick="toggleMenu()" class="no-select">...</button>
                <div id="menu-popup" class="hidden">
                    <a onclick="dialog.toggle('group-chat-form'); toggleMenu()">Create a group chat</a>
                    <a onclick="switchTheme(); toggleMenu()">Change theme</a>
                    <a href="https://github.com/lemniskett/konnichat/issues">Report issues</a>
                </div>
            </div>
            <div id="public-chat" class="contact-profile" onclick="chats.show('public-chat'); chats.triggerPublicMessages(); chats.selectContact('public-chat');">
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
            <div id="user-chat"></div>
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
                        <input id="input-fullname" class="higher hc-margin-top" name="hc-fullname" onchange="user.update('fullname')" autocomplete="off" maxlength="50">
                    </label>
                    <label class="hc-margin">
                        Status
                        <input id="input-status" class="higher hc-margin-top" name="hc-status" onchange="user.update('status')" autocomplete="off" maxlength="255">
                    </label>
                    <button onclick="dialog.toggle('password-form')" class="hc-margin">Update Password</button>
                    <a href="functions/logout.php" style="display: inherit;"><button class="hc-margin warn" onclick="">Log Out</button></a>
                </div>
            </div>
            <div class="public-chat">
                <div id="public-chat-container" class="hidden"></div>
                <div id="user-chat-container" class="hidden"></div>
            </div>
            <form class="hc-grid-col-2" id="chat-form">
                <textarea id="chat-input" autocomplete="off"></textarea>
                <button id="chat-submit" type="submit">Send</button>
            </form>
            <button id="button-back" onclick="chats.show('empty')">Back</button>
            <h3 id="chats-title"></h3>
            <button id="user-manual-button" onclick="dialog.toggle('user-manual')" class="no-select">?</button>
        </div>
    </div>
    <div id="dialog-container" class="hidden">
        <form class="hc-grid-col-1 hc-box higher dialog-form hidden" id="password-form">
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
            <button type="button" onclick="dialog.toggle('password-form')" class="hc-margin warn">Cancel</button>
        </form>
        <form class="hc-grid-col-1 hc-box higher dialog-form hidden" id="group-chat-form">
            <h3 class="hc-center-text">Create new group chat</h3>
            <label class="hc-margin">
                Group chat name
                <input type="text" id="input-group-chat-name" class="hc-margin-top evenhigher">
            </label>
            <button type="submit" class="hc-margin">Create</button>
            <button type="button" onclick="dialog.toggle('group-chat-form')" class="hc-margin warn">Cancel</button>
        </form>
        <div class="hc-grid-col-2 hc-box higher dialog-form hidden" id="user-manual">
            <h3 class="hc-grid-colspan-2 hc-center-text">Group Manual</h3>
            <span class="command hc-center-everything">!add &lt;username&gt;</span>
            <span class="command-desc">Add a member with username, multiple arguments supported.</span>
            <span class="command hc-center-everything">!del &lt;username&gt;</span>
            <span class="command-desc">Remove a member with username, multiple arguments supported.</span>
            <button type="button" onclick="dialog.toggle('user-manual')" class="hc-margin hc-grid-colspan-2">I Understand</button>
        </div>
        <div class="hc-grid-col-1 hc-box higher dialog-form hidden" id="profile-info">
            <h3 class="hc-center-text" id="view-fullname"></h3>
            <img class="view-picture" src="https://github.com/lemniskett/host/raw/master/blank-pp.png">
            <h4 class="hc-center-text" id="view-username"></h4>
            <p class="hc-center-text" id="view-status"></p>
            <button type="button" onclick="dialog.toggle('profile-info')" class="hc-margin">Close</button>
        </div>
    </div>
    <script src="index.js"></script>
</body>
</html>