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

    <!-- MAIN -->
    <div class="flex-1 md:ml-64">

        <!-- TOPBAR -->
        <div class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <h1 class="font-semibold">Dashboard Admin GA</h1>
            <span id="userInfo" class="text-sm text-gray-600"></span>
        </div>

        <!-- CONTENT -->
        <div class="p-6">

            <!-- CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                <div class="bg-white p-4 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Total Request</p>
                    <h2 id="totalRequest" class="text-2xl font-bold">0</h2>
                </div>

                <div class="bg-white p-4 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Pending</p>
                    <h2 id="pendingRequest" class="text-2xl font-bold text-yellow-500">0</h2>
                </div>

                <div class="bg-white p-4 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Approved</p>
                    <h2 id="approvedRequest" class="text-2xl font-bold text-green-600">0</h2>
                </div>

            </div>

            <!-- INFO -->
            <div class="bg-white p-6 rounded-xl shadow mt-6">
                <h2 class="font-semibold mb-2">Halo Admin GA 👋</h2>
                <p class="text-gray-600">
                    Kelola seluruh pengajuan perbaikan, approval, dan monitoring pekerjaan di sini.
                </p>
            </div>

        </div>

    </div>

</div>

<script>
const token = localStorage.getItem('token');
localStorage.getItem('token')
const user = JSON.parse(localStorage.getItem('user'));

// 🔥 PROTEKSI LOGIN
if (!token || !user) {
    localStorage.clear();
    window.location.href = '/login';
}

// USER INFO
document.getElementById('userInfo').innerText =
    user.name + ' (' + user.role + ')';

function goTo(url) {
    window.location.href = url;
}

function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = '/login';
}

function goToDashboard() {
    if (user?.role === 'super_admin') {
        window.location.href = '/dashboard-full';
    } else if (user?.role === 'admin') {
        if (user.system_type === 'lite') {
            window.location.href = '/dashboard-lite';
        } else {
            window.location.href = '/dashboard-admin';
        }
    } else if (user?.role === 'pic') {
        window.location.href = '/dashboard-pic';
    }
}

// 🔥 LOAD DATA (SUDAH ANTI ERROR & 401)
async function loadDashboard() {
    try {
        const res = await fetch('/api/requests', {
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await res.json();

        // ❌ HANDLE ERROR (TOKEN / API)
        if (!res.ok || !Array.isArray(data)) {
            console.error('API ERROR:', data);

            if (res.status === 401) {
                alert('Session habis, login ulang ya');
                localStorage.clear();
                window.location.href = '/login';
            }

            return;
        }

        // ✅ AMAN DIGUNAKAN
        const total = data.length;
        const pending = data.filter(i => i.status === 'pending').length;
        const approved = data.filter(i => i.status === 'approved').length;

        document.getElementById('totalRequest').innerText = total;
        document.getElementById('pendingRequest').innerText = pending;
        document.getElementById('approvedRequest').innerText = approved;

    } catch (err) {
        console.error('ERROR FETCH:', err);
        alert('Gagal koneksi ke server');
    }
}

// INIT
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    loadDashboard();
});
</script>

</body>
</html>