class Chats {
    currentContactSelected = '';
    currentSelected = '';

    show(classname) {
        const id = document.getElementById('chats');
        classes.forEach((item) => {
            id.getElementsByClassName(item)[0].style.display = 'none';
        });
        
        const selected = id.getElementsByClassName(classname)[0];
        const chatClasses = document.getElementById('chats').classList;
        const chatTitle = document.getElementById('chats-title');
        const backButton = document.getElementById('button-back');
        const chatForm = document.getElementById('chat-form');
        if(classname != 'empty') {
            chatClasses.add('selected');
            backButton.style.display = 'inherit';
            chatTitle.innerHTML = classname.replace('-', ' ');
        } else {
            chatClasses.remove('selected');
            backButton.style.display = 'none';
            chatTitle.innerHTML = '';
        }

        if(classname == 'public-chat') {
            chatForm.style.display = 'grid';
        } else {
            chatForm.style.display = 'none';
        }

        selected.style.display = 'block';
    }

    selectContact(contact) {
        this.show('public-chat');
        const chatTitle = document.getElementById('chats-title');
        const publicChatContainer = document.getElementById('public-chat-container');
        const userChatContainer = document.getElementById('user-chat-container');
        const manualButton = document.getElementById('user-manual-button');
        if(contact == 'public-chat') {
            publicChatContainer.classList.remove('hidden');
            userChatContainer.classList.add('hidden');
            this.currentSelected = 'public'
            manualButton.style.display = 'none';
            return 0;
        }
        publicChatContainer.classList.add('hidden');
        userChatContainer.classList.remove('hidden');
        if(this.currentContactSelected){
            document.getElementById(this.currentContactSelected).style.display = 'none';
        }
        document.getElementById(contact).style.display = 'block';
        manualButton.style.display = 'block';
        this.currentContactSelected = contact;
        this.currentSelected = contact.slice(6);
        chatTitle.innerHTML = document.getElementById(`user-chat-${this.currentSelected}`).getAttribute('data-groupchatname');
        userChatContainer.scrollTo(0, 999999);
    }

    async fetchPublicMessages() {
        const hash = await http.post('functions/public_chat.php', 'crc32=yes');
        if(hash == publicChatHash){
            return 0;
        } else {
            publicChatHash = hash;
        }
        const raw = await http.get('functions/public_chat.php');
        const cooked = await JSON.parse(raw);
        const container = document.getElementById('public-chat-container');
        container.innerHTML = '';
        let previousSender = '';
        let isWhom = '';
        let content = '';
        let together = '';
        cooked.content.forEach((item) => {
            const fullname = item.fullname;
            const username = item.username;
            const message = item.message.replace(/\n/g, '<br/>');
            if(myUsername == username) {
                isWhom = 'sent';
            } else {
                isWhom = 'received';
            }
            if(previousSender == username) {
                content = '';
                together = ' together';
            } else {
                content = `
                <div class="hc-grid-col-2 chats-contact">
                    <img class="chats-picture no-select" src="https://github.com/lemniskett/host/raw/master/blank-pp.png">
                    <span class="chats-name"><span class="chats-username" onclick="user.show('${username}')">${fullname}</span></span>
                </div>`;
                together = '';
            }
            container.innerHTML += `
            <div class="chats-${isWhom}${together}">
                ${content}
                <span class="chats-message">${message}</span>
            </div>`
            previousSender = item.username;
        });
        container.scrollTo(0, 999999);
    }

    async fetchUserMessages() {
        const hash = await http.post('functions/user_chat.php', 'crc32=yes');
        if(hash == userChatHash){
            return 0;
        } else {
            userChatHash = hash;
        }
        const raw = await http.get('functions/user_chat.php');
        const cooked = await JSON.parse(raw);
        const contactList = document.getElementById('user-chat');
        const userChatContainer = document.getElementById('user-chat-container');
        contactList.innerHTML = '';
        userChatContainer.innerHTML = '';
        let chatContent = '';
        cooked.content.forEach((item) => {
            let lastMessage = '';
            if(item.messages.length > 0){
                const length = item.messages.length - 1
                lastMessage = `${item.messages[length].fullname}: ${item.messages[length].message}`;
            } else {
                lastMessage = 'No chats yet :(';
            }
            contactList.innerHTML +=
            `<div id="user-chat-${item.groupChatID}" class="contact-profile" data-groupchatname="${item.groupChatName}" onclick="chats.selectContact('chats-${item.groupChatID}')">
                <div class="hc-center-everything no-select">
                    <img class="contact-picture" src="https://github.com/lemniskett/host/raw/master/blank-pp.png">
                </div>
                <div class="hc-margin-left name-container">
                    <div class="hc-grid-col-1" style="height: fit-content">
                        <span class="contact-name">${item.groupChatName}</span>
                        <span class="contact-message">${lastMessage}</span>
                    </div>
                </div>
            </div>`;
            let isWhom = '';
            let content = '';
            let together = '';
            let previousSender = '';
            if(this.currentSelected == item.groupChatID) {
                chatContent += `<div id="chats-${item.groupChatID}" style="display: block;">`;
            } else {
                chatContent += `<div id="chats-${item.groupChatID}" style="display: none;">`;
            }
            item.messages.forEach((message) => {
                if(myUsername == message.username) {
                    isWhom = 'sent';
                } else {
                    isWhom = 'received';
                }
                if(previousSender == message.username) {
                    content = '';
                    together = ' together';
                } else {
                    content = `
                    <div class="hc-grid-col-2 chats-contact">
                        <img class="chats-picture no-select" src="https://github.com/lemniskett/host/raw/master/blank-pp.png">
                        <span class="chats-name"><span class="chats-username" onclick="user.show('${message.username}')">${message.fullname}</span></span>
                    </div>`;
                    together = '';
                }
                chatContent += `
                <div class="chats-${isWhom}${together}"">
                    ${content}
                    <span class="chats-message">${message.message}</span>
                </div>`
                previousSender = message.username;
            });
            chatContent += `</div>`;
        });
        userChatContainer.innerHTML = chatContent;
        userChatContainer.scrollTo(0, 999999);
    }

    async triggerPublicMessages() {
        if (! publicChatTriggered){
            this.fetchPublicMessages()
            setInterval(() => {
                this.fetchPublicMessages();
            }, 1000); 
        } else {
            return 0;
        }
        publicChatTriggered = true;
    }
}

class User {
    async update(what) {
        const update = document.getElementById(`input-${what}`).value;
        //document.getElementById(`profile-${what}`).innerHTML = update;
        console.log(`Update ${what} to ${update}`);
        await http.post('functions/user_update.php', `type=${what}&${what}=${update}`)
    }

    async get() {
        const raw = await http.get('functions/user.php');
        const cooked = JSON.parse(raw);
        return cooked;
    }

    async show(who) {
        const raw = await http.post('functions/user.php', `username=${who}`);
        const cooked = JSON.parse(raw);
        document.getElementById('view-fullname').innerHTML = cooked.fullname;
        document.getElementById('view-username').innerHTML = `@${cooked.username}`;
        document.getElementById('view-status').innerHTML = cooked.status;
        dialog.toggle('profile-info');
    }
}

class Http {
    async get(path, get = '') {
        const raw = await fetch(`${path}?${get}`);
        const cooked = await raw.text();
        return cooked;
    }

    async post(path, post) {
        const raw = await fetch(path, {
            method: "POST", 
            body: post,
            headers: {
                "Content-type": "application/x-www-form-urlencoded"
            }
        });
        const cooked = await raw.text();
        return cooked;
    }
}

class Dialog {
    toggle(form) {
         const dialogContainer = document.getElementById('dialog-container');
         const formContainer = document.getElementById(form);
         dialogContainer.classList.toggle('hidden');
         formContainer.classList.toggle('hidden');
    }
}

function toggleMenu() {
    const menuPopUp = document.getElementById('menu-popup');
    menuPopUp.classList.toggle('hidden');
}

const http              = new Http();
const user              = new User();
const chats             = new Chats();
const dialog            = new Dialog();
const classes           = ['empty', 'profile', 'public-chat'];
let publicChatTriggered = false;
let publicChatHash      = '';
let userChatHash        = '';

(async () => {
    chats.show('empty');
    const data = await user.get();
    document.getElementById('chat-form').style.display = 'none'
    //document.getElementById('profile-fullname').innerHTML = data.fullname;
    //document.getElementById('profile-status').innerHTML = data.status;
    document.getElementById('input-status').value = data.status;
    document.getElementById('input-fullname').value = data.fullname;
    document.getElementById('password-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const currentPass = document.getElementById('input-current-pass').value
        const newPass = document.getElementById('input-new-pass').value
        const raw = await http.post('functions/user_update.php', `type=password&currentpass=${currentPass}&newpass=${newPass}`);
        const cooked = JSON.parse(raw);
        alert(cooked.status);
        dialog.toggle('password-form');
    });
    document.getElementById('group-chat-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const groupChatName = document.getElementById('input-group-chat-name').value        
        const raw = await http.post('functions/group_chat_add.php', `groupchatname=${groupChatName}`);
        const cooked = JSON.parse(raw);
        alert(cooked.status);
        dialog.toggle('group-chat-form');
    });
    document.getElementById('chat-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const message = document.getElementById('chat-input');
        const trimmedMessage = message.value.trim();
        let raw = '';
        if(chats.currentSelected == 'public'){
            raw = await http.post('functions/public_chat_update.php', `message=${trimmedMessage}`);
        } else {
            raw = await http.post('functions/user_chat_update.php', `message=${trimmedMessage}&groupchat=${chats.currentSelected}`);
        }
        const cooked = JSON.parse(raw);
        if (cooked.code == 200){
            message.value = '';
        }
        console.log(cooked.status);
    });
    document.getElementById("chat-input").addEventListener("keypress", (key)=> {
        if(!key.shiftKey && key.code == 'Enter'){
            key.target.form.dispatchEvent(new Event("submit"));
        }
    });
    window.addEventListener("resize", () => {
        const isPublic = chats.currentSelected == 'public';
        const isTouchScreen = window.sessionStorage.userUsesTouch == 'YES';
        if(! isTouchScreen) return 0;
        if(isPublic){
            document.getElementById('public-chat-container').scrollTo(0, 999999);
        } else {
            document.getElementById('user-chat-container').scrollTo(0, 999999);
        }
    });
    // Shamelessly copied from 
    // https://codeburst.io/the-only-way-to-detect-touch-with-javascript-7791a3346685
    window.addEventListener('touchstart', function onFirstTouch()  {
        window.sessionStorage.userUsesTouch = 'YES';
        window.removeEventListener('touchstart', onFirstTouch, false);
      }, false);
    setInterval(() => {
        chats.fetchUserMessages();
    }, 1000);
})();