class Chats {
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
                    <span class="chats-name">${fullname}</span>
                </div>`
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

    async triggerPublicMessages() {
        if (! publicChatTriggered){
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
        document.getElementById(`profile-${what}`).innerHTML = update;
        console.log(`Update ${what} to ${update}`);
        await http.post('functions/user_update.php', `type=${what}&${what}=${update}`)
    }

    async get() {
        const raw = await http.get('functions/user.php');
        const cooked = JSON.parse(raw);
        return cooked;
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

async function changePass(state) {
    const passContainer = document.getElementById('password-container')
    switch(state) {
        case 'open':
            passContainer.classList.remove('hide');
            break;
        case 'close':
            passContainer.classList.add('hide');
            break;
    }
}

const http              = new Http();
const user              = new User();
const chats             = new Chats();
const classes           = ['empty', 'profile', 'public-chat'];
let publicChatTriggered = false;
let publicChatHash      = '';

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
        currentPass = document.getElementById('input-current-pass').value
        newPass = document.getElementById('input-new-pass').value
        const raw = await http.post('functions/user_update.php', `type=password&current-pass=${currentPass}&new-pass=${newPass}`);
        const cooked = JSON.parse(raw);
        alert(cooked.status);
        changePass('close');
    });
    document.getElementById('chat-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const message = document.getElementById('chat-input');
        const raw = await http.post('functions/public_chat_update.php', `message=${message.value}`);
        const cooked = JSON.parse(raw);
        if (cooked.code == '200'){
            message.value = '';
        }
        console.log(cooked.status);
    });
    document.getElementById("chat-input").addEventListener("keypress", (key)=> {
        if(!key.shiftKey && key.code == 'Enter'){
            key.target.form.dispatchEvent(new Event("submit", {cancelable: true}));
        }
    });
})();