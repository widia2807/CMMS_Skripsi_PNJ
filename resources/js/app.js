import './bootstrap';
function goTo(url) {
    window.location.href = url;
}

function goToDashboard() {
    const user = JSON.parse(localStorage.getItem('user'));

    if (user?.role === 'pic') {
        window.location.href = '/dashboard-pic';
    } else if (user?.system_type === 'lite') {
        window.location.href = '/dashboard-lite';
    } else {
        window.location.href = '/dashboard-full';
    }
}

function logout() {
    localStorage.clear();
    window.location.href = '/login';
}