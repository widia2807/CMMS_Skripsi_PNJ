<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 flex items-center justify-center h-screen">

<div class="bg-white p-8 rounded-xl shadow-lg w-96">
    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

    <input id="email" type="email" placeholder="Email"
        class="w-full mb-4 p-2 border rounded">

    <input id="password" type="password" placeholder="Password"
        class="w-full mb-4 p-2 border rounded">

    <button onclick="login()"
        class="w-full bg-blue-600 text-white p-2 rounded">
        Login
    </button>

     <p class="text-center text-sm text-gray-500 mt-4">
        Doesn't have any account?
        <a href="/register" class="text-blue-600 font-semibold">Register</a>
    </p>
</div>
<script>
async function login() {
    const res = await fetch('/api/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json' // 🔥 wajib
        },
        body: JSON.stringify({
            email: document.getElementById('email').value,
            password: document.getElementById('password').value
        })
    });

    const data = await res.json();

if (!res.ok) {
    alert(data.message);
    return; // 🔥 STOP DI SINI
}

const user = data.user;

localStorage.setItem('token', data.token);
localStorage.setItem('user', JSON.stringify(user));
// 🔥 PIC
if (user.role === 'pic') {
    window.location.href = '/dashboard-pic';
}

// 🔥 ADMIN / SUPER ADMIN
else if (user.role === 'admin' || user.role === 'super_admin') {

    if (user.system_type === 'full') {
        window.location.href = '/dashboard-full';
    } else {
        window.location.href = '/dashboard-lite';
    }
}

// 🔥 TUKANG
else if (user.role === 'tukang') {
    window.location.href = '/dashboard-tukang';
}
}
</script>
</body>
</html>