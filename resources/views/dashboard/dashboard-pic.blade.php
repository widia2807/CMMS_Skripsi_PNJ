<!DOCTYPE html>
<html>
<head>
    <title>Dashboard PIC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body class="bg-gray-100">

<div class="flex h-screen">

    <!-- SIDEBAR -->
    <div id="sidebar"
     class="fixed z-50 inset-y-0 left-0 w-64 bg-gray-900 text-white p-5 transform -translate-x-full md:translate-x-0 transition duration-300">
        
        <button onclick="toggleSidebar()"
            class="absolute top-4 right-4 text-white md:hidden">
            ✖
        </button>

        <h2 class="text-xl font-bold mb-10">CMMS - PIC</h2>

        <ul class="space-y-3 text-sm">

            <li onclick="goTo('/dashboard-pic')"
                class="flex items-center gap-3 p-2 hover:bg-gray-800 cursor-pointer">
                <i data-feather="home"></i> Dashboard
            </li>

            <li onclick="goTo('/request')"
                class="flex items-center gap-3 p-2 hover:bg-gray-800 cursor-pointer">
                <i data-feather="tool"></i> Ajukan Perbaikan
            </li>

            <li onclick="goTo('/status')"
                class="flex items-center gap-3 p-2 hover:bg-gray-800 cursor-pointer">
                <i data-feather="list"></i> Status Pekerjaan
            </li>

            <li id="menu-spk"
                class="flex items-center gap-3 p-2 hover:bg-gray-800 cursor-pointer">
                <i data-feather="file-text"></i> SPK
            </li>

            <li onclick="logout()"
                class="flex items-center gap-3 p-2 hover:bg-red-600 cursor-pointer mt-6">
                <i data-feather="log-out"></i> Logout
            </li>
        </ul>
    </div>

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

            <h1 class="font-semibold">Dashboard PIC</h1>

            <div>
                <span id="userInfo" class="text-sm text-gray-600"></span>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="p-6 mt-4">

            <!-- CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                <div class="bg-white p-4 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Total Pengajuan</p>
                    <h2 id="totalPengajuan" class="text-2xl font-bold">0</h2>
                </div>

                <div class="bg-white p-4 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Sedang Berjalan</p>
                    <h2 id="onProgress" class="text-2xl font-bold text-blue-600">0</h2>
                </div>

                <div class="bg-white p-4 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Selesai</p>
                    <h2 id="done" class="text-2xl font-bold text-green-600">0</h2>
                </div>

            </div>

            <!-- ACTIVITY -->
            <div id="activityCard" class="bg-white p-4 rounded-xl shadow mt-6 relative">

                <button onclick="closeActivity()"
                    class="absolute top-3 right-3 text-gray-400 hover:text-red-500">
                    ✖
                </button>

                <h2 class="font-semibold mb-3">Aktivitas Terbaru</h2>
                <ul id="recentActivity" class="text-sm text-gray-600 space-y-2"></ul>
            </div>

            <!-- WELCOME -->
            <div id="welcomeCard" class="bg-white p-6 rounded-xl shadow mt-6 relative">

                <button onclick="closeWelcome()"
                    class="absolute top-3 right-3 text-gray-400 hover:text-red-500">
                    ✖
                </button>

                <h2 class="text-lg font-semibold mb-2">Welcome PIC 👋</h2>
                <p class="text-gray-600">
                    Ajukan perbaikan dan pantau pekerjaan Anda di sini.
                </p>

            </div>

        </div>

    </div>

</div>

<script>
const user = JSON.parse(localStorage.getItem('user'));

// USER INFO
document.getElementById('userInfo').innerText =
    user.name + ' (' + user.system_type + ')';

// 🔥 MODE LITE → HIDE SPK
if (user.system_type === 'lite') {
    document.getElementById('menu-spk').style.display = 'none';
}

// FUNCTION
function goTo(url) {
    window.location.href = url;
}

function logout() {
    localStorage.clear();
    window.location.href = '/login';
}

function closeWelcome() {
    document.getElementById('welcomeCard').style.display = 'none';
}

function closeActivity() {
    document.getElementById('activityCard').style.display = 'none';
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}

feather.replace();

// LOAD DASHBOARD PIC
async function loadDashboard() {
    try {
        const res = await fetch('/api/pic/dashboard', {
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        });

        const data = await res.json();
        console.log('Response:', data);

        // 🔥 TAMBAH INI
        if (!res.ok) {
            console.error('API Error:', data.message);
            return;
        }

        document.getElementById('totalPengajuan').innerText = data.total ?? 0;
        document.getElementById('onProgress').innerText = data.progress ?? 0;
        document.getElementById('done').innerText = data.done ?? 0;

    } catch (error) {
        console.error('Gagal load dashboard:', error);
    }
}
// LOAD ACTIVITY
async function loadActivity() {
    try {
        const res = await fetch('/api/pic/activity', {
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
        });

        const data = await res.json();

        // Guard: make sure data is an array before forEach
        if (!res.ok || !Array.isArray(data)) {
            console.error('Unexpected response:', data);
            return;
        }

        const container = document.getElementById('recentActivity');
        container.innerHTML = '';

        data.forEach(item => {
            const li = document.createElement('li');
            li.innerHTML = `✔ ${item.description}`;
            container.appendChild(li);
        });

    } catch (error) {
        console.error('Gagal load activity:', error);
    }
}

// INIT
document.addEventListener('DOMContentLoaded', () => {
    loadDashboard();
    loadActivity();
});
</script>

</body>
</html>