<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
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
        <h2 class="text-xl font-bold mb-10">CMMS</h2>

        <ul class="space-y-3 text-sm">

            <li onclick="goTo('/dashboard-full')"
                class="flex items-center gap-3 p-2 hover:bg-gray-800 cursor-pointer">
                <i data-feather="home"></i> Dashboard
            </li>

              <li onclick="goTo('/cabang')"
                class="flex items-center gap-3 p-2 hover:bg-gray-800 cursor-pointer">
                <i data-feather="briefcase"></i> Cabang
            </li>

           <li onclick="goTo('/users')"
                class="menu-item p-2 rounded flex items-center gap-3 cursor-pointer hover:bg-gray-800 transition duration-200">
                <i data-feather="users"></i> User Management
            </li>
            
            <li class="menu-item p-2 rounded flex items-center gap-3 cursor-pointer hover:bg-gray-800 transition duration-200">
                <i data-feather="file-text"></i> Laporan
            </li>

            <li class="menu-item p-2 rounded flex items-center gap-3 cursor-pointer hover:bg-gray-800 transition duration-200">
                <i data-feather="settings"></i> Settings
            </li>
</ul>
    </div>
    <div id="overlay"
     class="fixed inset-0 bg-black opacity-40 hidden md:hidden"
     onclick="toggleSidebar()">
    </div>

    <!-- MAIN -->
   <div class="flex-1 md:ml-64">

        <!-- TOPBAR -->
        <button onclick="toggleSidebar()"
            class="md:hidden text-xl mr-4">
            <i data-feather="menu"></i>
        </button>
        
        <div class="bg-white shadow px-6 py-4 flex items-center justify-between">

            <h1 class="font-semibold">Dashboard</h1>

            <div>
                <span class="text-sm text-gray-600">Super Admin</span>
            </div>

        </div>

        <!-- CONTENT -->
        <div class="p-6 mt-4">

            <!-- CARDS -->
           <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <div class="bg-white p-4 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Total Cabang</p>
                    <h2 id="totalCabang"class="text-2xl font-bold">3</h2>
                </div>

                <div class="bg-white p-4 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Total User</p>
                    <h2 id="totalUser"class="text-2xl font-bold">12</h2>
                </div>

                <div class="bg-white p-4 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">User Pending</p>
                    <h2 id="pendingUser"class="text-2xl font-bold text-yellow-500">2</h2>
                </div>

                <div class="bg-white p-4 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Active User</p>
                    <h2 id="activeUser" class="text-2xl font-bold text-green-600">0</h2>
                </div>

            </div>

            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-xl mt-6 flex justify-between items-center">
            <div>
                <p class="text-sm text-yellow-700">⚠️ 2 User menunggu approval</p>
                </div>
                <button class="bg-yellow-500 text-white px-4 py-2 rounded">
                    Lihat
                </button>

            </div>

            <div class="flex gap-4 mt-6">

                <button class="bg-blue-600 text-white px-4 py-2 rounded">
                    + Tambah Cabang
                </button>

                <button class="bg-green-600 text-white px-4 py-2 rounded">
                    + Tambah User
                </button>

            </div>

            <div id="activityCard" class="bg-white p-4 rounded-xl shadow mt-6 relative">

                <button onclick="closeActivity()"
                    class="absolute top-3 right-3 text-gray-400 hover:text-red-500">
                    ✖
                </button>
                <h2 class="font-semibold mb-3">Recent Activity</h2>

                <ul id="recentActivity" class="text-sm text-gray-600 space-y-2"></ul>
            </div>

            <!-- WELCOME -->
            <div id="welcomeCard" class="bg-white p-6 rounded-xl shadow mt-6 relative">

    <!-- tombol close -->
    <button onclick="closeWelcome()"
        class="absolute top-3 right-3 text-gray-400 hover:text-red-500">
        ✖
    </button>

    <h2 class="text-lg font-semibold mb-2">Welcome Super Admin 👋</h2>

    <p class="text-gray-600">
        Kelola cabang, user, dan sistem CMMS Anda di sini.
    </p>

    </div>

        </div>

    </div>

</div>
<script>
function closeWelcome() {
    document.getElementById('welcomeCard').style.display = 'none';
}

function goTo(url) {
    window.location.href = url;
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

feather.replace()

// ACTIVE MENU
const menuItems = document.querySelectorAll('.menu-item');

menuItems.forEach(item => {
    item.addEventListener('click', () => {
        menuItems.forEach(i => i.classList.remove('bg-gray-800'));
        item.classList.add('bg-gray-800');
    });
});

// LOAD ACTIVITY
async function loadActivity() {
    try {
        const res = await fetch('/api/dashboard/activity');
        const data = await res.json();

        const container = document.getElementById('recentActivity');

        if (!container) return; // biar gak error kalau elemen gak ada

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

// LOAD DASHBOARD
async function loadDashboard() {
    try {
        const res = await fetch('/api/dashboard');
        const data = await res.json();

        document.getElementById('totalCabang').innerText = data.total_cabang ?? 0;
        document.getElementById('totalUser').innerText = data.total_user ?? 0;
        document.getElementById('pendingUser').innerText = data.pending_user ?? 0;
        document.getElementById('activeUser').innerText = data.active_user ?? 0;

    } catch (error) {
        console.error('Gagal load dashboard:', error);
    }
}



// JALANKAN SETELAH PAGE READY
document.addEventListener('DOMContentLoaded', () => {
    loadDashboard();
    loadActivity();
});
</script>
</body>
</html>