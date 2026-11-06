// Sesuaikan jika nama field di backend-mu berbeda (lihat Tahap 0)
const FIELD = {
    tahun: 'tahun',
    kategori: 'category_id'
};

let booksCache = [];
let categoriesCache = [];

async function renderBooks() {
    const app = document.getElementById('app');
    app.innerHTML = '<p class="text-gray-500">Memuat data buku...</p>';

    try {
        const [booksRes, catsRes] = await Promise.all([
            BookService.getAll(),
            CategoryService.getAll()
        ]);
        booksCache = unwrap(booksRes);
        categoriesCache = unwrap(catsRes);

        const rows = booksCache.length
            ? booksCache.map(b => `
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">${escapeHtml(b.id)}</td>
                    <td class="px-4 py-2">${escapeHtml(b.judul)}</td>
                    <td class="px-4 py-2">${escapeHtml(b.penulis)}</td>
                    <td class="px-4 py-2">${escapeHtml(b[FIELD.tahun])}</td>
                    <td class="px-4 py-2">${escapeHtml(b.stok)}</td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        <button onclick="openBookModal(${Number(b.id)})" class="text-blue-600 hover:underline mr-3">Edit</button>
                        <button onclick="deleteBook(${Number(b.id)})" class="text-red-600 hover:underline">Hapus</button>
                    </td>
                </tr>`).join('')
            : '<tr><td colspan="6" class="px-4 py-4 text-center text-gray-500">Belum ada data buku.</td></tr>';

        app.innerHTML = `
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Daftar Buku</h2>
                <button onclick="openBookModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah Buku</button>
            </div>
            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Judul</th>
                            <th class="px-4 py-2">Penulis</th>
                            <th class="px-4 py-2">Tahun</th>
                            <th class="px-4 py-2">Stok</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>`;
    } catch (err) {
        app.innerHTML = '<p class="text-red-600">Gagal memuat buku: ' + escapeHtml(err.message) + '</p>';
        showToast(err.message, 'error');
    }
}

function openBookModal(id) {
    const book = id ? booksCache.find(b => Number(b.id) === Number(id)) : null;
    const isEdit = !!book;

    const options = categoriesCache.map(c => `
        <option value="${escapeHtml(c.id)}" ${book && String(book[FIELD.kategori]) === String(c.id) ? 'selected' : ''}>
            ${escapeHtml(c.nama)}
        </option>`).join('');

    closeBookModal();
    const modal = document.createElement('div');
    modal.id = 'book-modal';
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-40';
    modal.innerHTML = `
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-bold mb-4">${isEdit ? 'Edit Buku' : 'Tambah Buku'}</h3>
            <div class="space-y-3">
                <input id="f-judul" type="text" placeholder="Judul" class="w-full border rounded px-3 py-2" value="${isEdit ? escapeHtml(book.judul) : ''}">
                <input id="f-penulis" type="text" placeholder="Penulis" class="w-full border rounded px-3 py-2" value="${isEdit ? escapeHtml(book.penulis) : ''}">
                <input id="f-tahun" type="number" placeholder="Tahun" class="w-full border rounded px-3 py-2" value="${isEdit ? escapeHtml(book[FIELD.tahun]) : ''}">
                <input id="f-stok" type="number" min="0" placeholder="Stok" class="w-full border rounded px-3 py-2" value="${isEdit ? escapeHtml(book.stok) : ''}">
                <select id="f-kategori" class="w-full border rounded px-3 py-2">${options}</select>
            </div>
            <div class="flex justify-end gap-2 mt-5">
                <button onclick="closeBookModal()" class="px-4 py-2 rounded border">Batal</button>
                <button onclick="saveBook(${isEdit ? Number(book.id) : 'null'})" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Simpan</button>
            </div>
        </div>`;
    document.body.appendChild(modal);
}

function closeBookModal() {
    const modal = document.getElementById('book-modal');
    if (modal) modal.remove();
}

async function saveBook(id) {
    const data = {
        judul: document.getElementById('f-judul').value.trim(),
        penulis: document.getElementById('f-penulis').value.trim(),
        [FIELD.tahun]: Number(document.getElementById('f-tahun').value),
        stok: Number(document.getElementById('f-stok').value),
        [FIELD.kategori]: Number(document.getElementById('f-kategori').value)
    };

    if (!data.judul || !data.penulis) {
        showToast('Judul dan penulis wajib diisi', 'error');
        return;
    }

    try {
        if (id) {
            await BookService.update(id, data);
            showToast('Buku berhasil diperbarui');
        } else {
            await BookService.create(data);
            showToast('Buku berhasil ditambahkan');
        }
        closeBookModal();
        renderBooks();
    } catch (err) {
        showToast(err.message, 'error');
    }
}

async function deleteBook(id) {
    if (!confirm('Hapus buku ini?')) return;
    try {
        await BookService.remove(id);
        showToast('Buku berhasil dihapus');
        renderBooks();
    } catch (err) {
        showToast(err.message, 'error');
    }
}
