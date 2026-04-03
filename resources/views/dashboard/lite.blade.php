<h1>Dashboard LITE</h1>
<script>
const user = localStorage.getItem('user');

if (!user) {
    window.location.href = '/login';
}
</script>