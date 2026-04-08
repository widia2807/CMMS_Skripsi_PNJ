<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 flex items-center justify-center h-screen">

<div class="bg-white p-8 rounded-2xl shadow-2xl w-96">

    <!-- HEADER -->
    <h2 class="text-2xl font-bold text-center mb-2">Create Account</h2>
    <p class="text-gray-500 text-center mb-6 text-sm">
        CMMS System Registration
    </p>

    <!-- INPUT -->
    <input id="name" placeholder="Full Name"
        class="w-full mb-4 p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">

    <input id="email" placeholder="Email"
        class="w-full mb-4 p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">

    <input id="password" type="password" placeholder="Password"
        class="w-full mb-4 p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">

    <!-- SYSTEM TYPE -->
    <select id="system_type"
        class="w-full mb-4 p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
        <option value="full">🚀 Full System (Kampus)</option>
        <option value="lite">⚡ Lite System (Magang)</option>
    </select>

    <input id="company_name" placeholder="Company Name"
        class="w-full mb-6 p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">

    <!-- BUTTON -->
    <button type="button" onclick="register()"
    class="w-full bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg font-semibold transition">
    Register
</button>

    <!-- FOOTER -->
    <p class="text-center text-sm text-gray-500 mt-4">
        Already have an account?
        <a href="/login" class="text-blue-600 font-semibold">Login</a>
    </p>

</div>

<script>
async function register() {
    const res = await fetch('/api/register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            

        },
        body: JSON.stringify({
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            system_type: document.getElementById('system_type').value,
            company_name: document.getElementById('company_name').value
        })
    });

    const data = await res.json();

    if (res.ok) {
        alert("Register berhasil!");
        window.location.href = '/login';
    } else {
        alert(data.message);
    }
}
</script>

</body>
</html>