const routes = {
    dashboard: renderDashboard,
    buku: renderBooks,
    kategori: () => {
        document.getElementById('app').innerHTML =
            '<p class="text-gray-500">Halaman Kategori akan dibuat di langkah berikutnya.</p>';
    }
};

function navigate() {
    const page = location.hash.replace('#', '') || 'dashboard';
    const handler = routes[page] || routes.dashboard;

    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.toggle('active', link.getAttribute('href') === '#' + page);
    });

    handler();
}

window.addEventListener('hashchange', navigate);
window.addEventListener('DOMContentLoaded', navigate);
