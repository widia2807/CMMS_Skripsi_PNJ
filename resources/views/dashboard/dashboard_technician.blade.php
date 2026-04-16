<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Technician</title>
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

            <button onclick="toggleSidebar()" class="md:hidden text-xl">
                <i data-feather="menu"></i>
            </button>

            <h1 class="font-semibold">Dashboard Technician</h1>

            <div>
                <span class="text-sm text-gray-600">
                    {{ auth()->user()->name ?? 'Technician' }}
                </span>
            </div>

        </div>

        <!-- CONTENT -->
        <div class="p-6 mt-4">

            <!-- CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <!-- PEKERJAAN MASUK -->
                <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
                    <p class="text-gray-500 text-sm">Pekerjaan Masuk</p>
                    <h2 id="incomingJob" class="text-3xl font-bold text-blue-600">0</h2>
                </div>

                <!-- PEKERJAAN SELESAI -->
                <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
                    <p class="text-gray-500 text-sm">Pekerjaan Selesai</p>
                    <h2 id="completedJob" class="text-3xl font-bold text-green-600">0</h2>
                </div>

            </div>

            <!-- OPTIONAL: LIST PEKERJAAN -->
            <div class="bg-white p-4 rounded-xl shadow mt-6">
                <h2 class="font-semibold mb-3">Pekerjaan Terbaru</h2>
                <ul id="jobList" class="text-sm text-gray-600 space-y-2"></ul>
            </div>

            <!-- WELCOME -->
            <div class="bg-white p-6 rounded-xl shadow mt-6">
                <h2 class="text-lg font-semibold mb-2">Welcome 👋</h2>
                <p class="text-gray-600">
                    Kerjakan tugas yang diberikan dan update status pekerjaan Anda.
                </p>
            </div>

        </div>

    </div>

</div>

<script>
const user = JSON.parse(localStorage.getItem('user'));

if (!user) {
    window.location.href = '/login';
}

// SIDEBAR
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}

function logout() {
    localStorage.clear();
    window.location.href = '/login';
}

async function loadDashboard() {
    try {
        const res = await fetch('/api/dashboard-technician', {
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        });

        const data = await res.json();

        if (!res.ok) {
            console.error('API ERROR:', data);
            return;
        }

        document.getElementById('incomingJob').innerText = data.incoming_jobs ?? 0;
        document.getElementById('completedJob').innerText = data.completed_jobs ?? 0;

    } catch (err) {
        console.error(err);
    }
}

function goTo(url) {
    window.location.href = url;
}

// INIT
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    loadDashboard();
    
});
</script>

</body>
</html>