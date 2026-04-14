<!DOCTYPE html>

<html>
<head>
    <title>Dashboard Admin GA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body class="bg-gray-100">
@include('components.sidebar')
<div class="flex h-screen">


<!-- OVERLAY -->
<div id="overlay"
 class="fixed inset-0 bg-black opacity-40 hidden md:hidden"
 onclick="toggleSidebar()">
</div>

<!-- MAIN -->
<div class="flex-1 md:ml-64">

    <!-- TOPBAR -->
    <div class="bg-white shadow px-6 py-4 flex items-center justify-between">

        <button onclick="toggleSidebar()" class="md:hidden text-xl mr-4">
            <i data-feather="menu"></i>
        </button>

        <h1 class="font-semibold">Dashboard Admin GA</h1>

        <div>
            <span id="userInfo" class="text-sm text-gray-600"></span>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="p-6 mt-4">

        <!-- CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            <div class="bg-white p-4 rounded-xl shadow">
                <p class="text-gray-500 text-sm">Total User</p>
                <h2 id="totalUser" class="text-2xl font-bold">0</h2>
            </div>

            <div class="bg-white p-4 rounded-xl shadow">
                <p class="text-gray-500 text-sm">User Pending</p>
                <h2 id="pendingUser" class="text-2xl font-bold text-yellow-500">0</h2>
            </div>

            <div class="bg-white p-4 rounded-xl shadow">
                <p class="text-gray-500 text-sm">User Active</p>
                <h2 id="activeUser" class="text-2xl font-bold text-green-600">0</h2>
            </div>

        </div>

        <!-- INFO BOX -->
        <div class="bg-white p-6 rounded-xl shadow mt-6">
            <h2 class="text-lg font-semibold mb-2">Halo Admin GA 👋</h2>
            <p class="text-gray-600">
                Anda dapat mengelola user, melakukan approval, dan memantau aktivitas sistem di sini.
            </p>
        </div>

    </div>

</div>


</div>

<script>
const user = JSON.parse(localStorage.getItem('user'));

// 🔥 PROTEKSI LOGIN
if (!user) {
    window.location.href = '/login';
}

// USER INFO
document.getElementById('userInfo').innerText =
    user.name + ' (' + user.role + ')';

// NAV
function goTo(url) {
    window.location.href = url;
}

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
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
    document.getElementById('overlay').classList.toggle('hidden');
}

// LOAD DATA
async function loadLiteDashboard() {
    const token = localStorage.getItem('token');
    console.log('TOKEN KIRIM:', localStorage.getItem('token'));

if (!token) {
    window.location.href = '/login';
}
    try {
        const res = await fetch('/api/lite/dashboard', {
    headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('token'),
        'Accept': 'application/json'
    }
});

        const data = await res.json();
        console.log('LITE:', data);

        if (!res.ok) {
            console.error(data);
            return;
        }

        document.getElementById('totalUser').innerText = data.total ?? 0;
        document.getElementById('pendingUser').innerText = data.pending ?? 0;
        document.getElementById('activeUser').innerText = data.active ?? 0;

    } catch (error) {
        console.error('Error:', error);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadLiteDashboard();
    localStorage.getItem('user')
});
</script>

</body>
</html>

