const routes = {
    dashboard: renderDashboard,
    buku: renderBooks,
    kategori: renderCategories
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
