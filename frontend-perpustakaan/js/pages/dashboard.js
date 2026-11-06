async function renderDashboard() {
    const app = document.getElementById('app');
    app.innerHTML = '<p class="text-gray-500">Memuat dashboard...</p>';

    try {
        const [booksRes, catsRes] = await Promise.all([
            BookService.getAll(),
            CategoryService.getAll()
        ]);
        const books = unwrap(booksRes);
        const categories = unwrap(catsRes);

        const latest = [...books]
            .sort((a, b) => Number(b.id) - Number(a.id))
            .slice(0, 5);

        const latestHtml = latest.length
            ? latest.map(b => `
                <li class="py-2 border-b last:border-0">
                    <span class="font-medium">${escapeHtml(b.judul)}</span>
                    <span class="text-gray-500 text-sm"> - ${escapeHtml(b.penulis)}</span>
                </li>`).join('')
            : '<li class="py-2 text-gray-500">Belum ada buku.</li>';

        app.innerHTML = `
            <h2 class="text-xl font-bold mb-4">Dashboard</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div class="bg-blue-500 text-white rounded-lg p-6 shadow">
                    <p class="text-sm opacity-90">Total Buku</p>
                    <p class="text-4xl font-bold">${books.length}</p>
                </div>
                <div class="bg-orange-500 text-white rounded-lg p-6 shadow">
                    <p class="text-sm opacity-90">Kategori</p>
                    <p class="text-4xl font-bold">${categories.length}</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold mb-2">Buku Terbaru</h3>
                <ul>${latestHtml}</ul>
            </div>`;
    } catch (err) {
        app.innerHTML = '<p class="text-red-600">Gagal memuat dashboard: ' + escapeHtml(err.message) + '</p>';
        showToast(err.message, 'error');
    }
}
