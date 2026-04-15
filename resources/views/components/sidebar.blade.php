<div id="sidebar"
 class="fixed z-50 inset-y-0 left-0 w-64 bg-gray-900 text-white p-5 transform -translate-x-full md:translate-x-0 transition duration-300">

    <!-- CLOSE BUTTON -->
    <button onclick="toggleSidebar()" class="absolute top-4 right-4 text-white md:hidden text-lg">✖</button>

    <!-- TITLE -->
    <h2 class="text-xl font-bold mb-10">
        CMMS - <span id="roleText">USER</span>
    </h2>

    <!-- MENU -->
    <ul class="space-y-2 text-sm">

        <!-- DASHBOARD -->
        <li onclick="goToDashboard()" class="menu-item flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800 cursor-pointer">
            <i data-feather="home" class="w-5 h-5"></i>
            <span>Dashboard</span>
        </li>

        <!-- ADMIN MENU -->
        <li id="menu-perbaikan" class="menu-item hidden flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800 cursor-pointer" onclick="goTo('/request-list')">
            <i data-feather="tool" class="w-5 h-5"></i>
            <span>Perbaikan Gedung</span>
        </li>

        <li id="menu-maintenance" class="menu-item hidden flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800 cursor-pointer" onclick="goTo('/maintenance')">
            <i data-feather="calendar" class="w-5 h-5"></i>
            <span>Maintenance</span>
        </li>

        <li id="menu-technician" class="menu-item hidden flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800 cursor-pointer" onclick="goTo('/technicians')">
            <i data-feather="users" class="w-5 h-5"></i>
            <span>Tukang</span>
        </li>

        <li id="menu-tools" class="menu-item hidden flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800 cursor-pointer" onclick="goTo('/borrow-tools')">
            <i data-feather="package" class="w-5 h-5"></i>
            <span>Peminjaman Alat</span>
        </li>

        <li id="menu-spk" class="menu-item hidden flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800 cursor-pointer" onclick="goTo('/spk')">
            <i data-feather="file-text" class="w-5 h-5"></i>
            <span>SPK</span>
        </li>

        <li id="menu-report" class="menu-item hidden flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800 cursor-pointer" onclick="goTo('/reports')">
            <i data-feather="bar-chart" class="w-5 h-5"></i>
            <span>Laporan</span>
        </li>

        <li id="menu-user" class="menu-item hidden flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800 cursor-pointer" onclick="goTo('/users')">
            <i data-feather="user" class="w-5 h-5"></i>
            <span>User</span>
        </li>

        <li id="menu-cabang" class="menu-item hidden flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800 cursor-pointer" onclick="goTo('/cabang')">
            <i data-feather="briefcase" class="w-5 h-5"></i>
            <span>Cabang</span>
        </li>

        <!-- PIC -->
        <li id="menu-pic" class="menu-item hidden flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800 cursor-pointer" onclick="goTo('/request')">
            <i data-feather="tool" class="w-5 h-5"></i>
            <span>Ajukan Perbaikan</span>
        </li>

        <li id="menu-status" class="menu-item hidden flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800 cursor-pointer" onclick="goTo('/status')">
            <i data-feather="list" class="w-5 h-5"></i>
            <span>Status</span>
        </li>

        <!-- LOGOUT -->
        <li onclick="logout()" class="mt-6 flex items-center gap-3 px-3 py-2 rounded hover:bg-red-600 cursor-pointer">
            <i data-feather="log-out" class="w-5 h-5"></i>
            <span>Logout</span>
        </li>

    </ul>
</div>
<div id="sidebarOverlay" 
    onclick="toggleSidebar()" 
    class="fixed inset-0 bg-black/40 hidden z-40 md:hidden">
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {

    const user = JSON.parse(localStorage.getItem('user'));

    if (!user) {
        window.location.href = '/login';
        return;
    }

    // tampilkan role di atas
    document.getElementById('roleText').innerText = user.role.toUpperCase();

    const menuConfig = {
    admin: [
        'menu-perbaikan',
        'menu-maintenance',
        'menu-technician',
        'menu-tools',
        'menu-spk',
        'menu-report'
    ],

    super_admin: [
        'menu-user',
        'menu-cabang'
    ],

    pic: [
        'menu-pic',
        'menu-status'
    ],

    technician: [
        'menu-status'
    ]
};

    // tampilkan menu sesuai role
    if (menuConfig[user.role]) {
        menuConfig[user.role].forEach(id => {
            document.getElementById(id)?.classList.remove('hidden');
        });
    }

    // tambahan admin GA lite
    if (user.role === 'admin' && user.system_type === 'lite') {
        ['menu-user','menu-cabang'].forEach(id => {
            document.getElementById(id)?.classList.remove('hidden');
        });
    }

    feather.replace();
});
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}

function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = '/login';
}
</script>