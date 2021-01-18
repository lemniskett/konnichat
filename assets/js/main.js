function switchTheme() {
    switch(window.localStorage.theme) {
        case 'light':
            window.localStorage.theme = changeTheme('dark');
            break;
        case 'dark':
            window.localStorage.theme = changeTheme('light');
            break;
        default:
            return 255;
    }
}

function changeTheme(theme) {
    const style = document.documentElement.style;
    switch(theme) {
        case 'light':
            style.setProperty('--bg', 'rgba(255, 255, 255, 0.9)');
            style.setProperty('--altbg', 'rgba(255, 255, 255, 0.9)');
            style.setProperty('--altbg2', 'rgba(255, 255, 255, 0.9)');
            style.setProperty('--hover', 'rgba(0, 0, 0, 0.05)');
            style.setProperty('--bgsolid', ' rgba(250, 250, 250, 1)');
            style.setProperty('--altbgsolid', ' rgba(247, 247, 247, 1)');
            style.setProperty('--accent', '#0097a7');
            style.setProperty('--warn', '#d32f2f');
            style.setProperty('--altwarn', '#c62828');
            style.setProperty('--traccent', '#0097a755');
            style.setProperty('--trwarn', '#d32f2f55');
            style.setProperty('--altaccent', '#00838f');
            style.setProperty('--fg', 'rgba(0, 0, 0, 1)');
            style.setProperty('--trfg', 'rgba(0, 0, 0, 0.6)');
            return 'light';
        case 'dark':
            style.setProperty('--bg', 'rgba(18, 18, 18, 0.9)');
            style.setProperty('--altbg', 'rgba(30, 30, 30, 0.9)');
            style.setProperty('--altbg2', 'rgba(42, 42, 42, 0.9)');
            style.setProperty('--hover', 'rgba(255, 255, 255, 0.05)');
            style.setProperty('--bgsolid', 'rgba(18, 18, 18, 1)');
            style.setProperty('--altbgsolid', 'rgba(12, 12, 12, 1)');
            style.setProperty('--accent', '#80deea');
            style.setProperty('--warn', '#ef9a9a');
            style.setProperty('--altwarn', '#ffcdd2');
            style.setProperty('--traccent', '#80deea55');
            style.setProperty('--trwarn', '#ef9a9a55');
            style.setProperty('--altaccent', '#b2ebf2');
            style.setProperty('--fg', 'rgba(245, 245, 245, 1)');
            style.setProperty('--trfg', 'rgba(245, 245, 245, 0.6)');
            return 'dark';
        default:
            return 255;
    }
}

if(! window.localStorage.theme) {
    window.localStorage.theme = changeTheme('light');
} else {
    changeTheme(window.localStorage.theme);
}