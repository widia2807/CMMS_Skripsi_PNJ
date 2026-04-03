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

    console.log('LOGIN RESPONSE:', data); // debug

    if (res.ok) {
        // ✅ SIMPAN TOKEN (INI YANG KAMU LUPA)
        localStorage.setItem('token', data.token);

        // optional
        localStorage.setItem('user', JSON.stringify(data.user));

        // redirect
        if (data.system_type === 'full') {
            window.location.href = '/dashboard-full';
        } else {
            window.location.href = '/dashboard-lite';
        }
    } else {
        alert(data.message);
    }
}
</script>
</body>
</html>