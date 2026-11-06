// Sesuaikan jika nama field di backend-mu berbeda
const CAT_FIELD = {
    nama: 'nama',
    deskripsi: 'deskripsi'
};

let categoriesList = [];

async function renderCategories() {
    const app = document.getElementById('app');
    app.innerHTML = '<p class="text-gray-500">Memuat data kategori...</p>';

    try {
        categoriesList = unwrap(await CategoryService.getAll());

        const rows = categoriesList.length
            ? categoriesList.map(c => `
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">${escapeHtml(c.id)}</td>
                    <td class="px-4 py-2">${escapeHtml(c[CAT_FIELD.nama])}</td>
                    <td class="px-4 py-2">${escapeHtml(c[CAT_FIELD.deskripsi])}</td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        <button onclick="openCategoryModal(${Number(c.id)})" class="text-blue-600 hover:underline mr-3">Edit</button>
                        <button onclick="deleteCategory(${Number(c.id)})" class="text-red-600 hover:underline">Hapus</button>
                    </td>
                </tr>`).join('')
            : '<tr><td colspan="4" class="px-4 py-4 text-center text-gray-500">Belum ada data kategori.</td></tr>';

        app.innerHTML = `
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Daftar Kategori</h2>
                <button onclick="openCategoryModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah Kategori</button>
            </div>
            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Deskripsi</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>`;
    } catch (err) {
        app.innerHTML = '<p class="text-red-600">Gagal memuat kategori: ' + escapeHtml(err.message) + '</p>';
        showToast(err.message, 'error');
    }
}

function openCategoryModal(id) {
    const cat = id ? categoriesList.find(c => Number(c.id) === Number(id)) : null;
    const isEdit = !!cat;

    closeCategoryModal();
    const modal = document.createElement('div');
    modal.id = 'category-modal';
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-40';
    modal.innerHTML = `
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-bold mb-4">${isEdit ? 'Edit Kategori' : 'Tambah Kategori'}</h3>
            <div class="space-y-3">
                <input id="c-nama" type="text" placeholder="Nama kategori" class="w-full border rounded px-3 py-2" value="${isEdit ? escapeHtml(cat[CAT_FIELD.nama]) : ''}">
                <textarea id="c-deskripsi" rows="3" placeholder="Deskripsi" class="w-full border rounded px-3 py-2">${isEdit ? escapeHtml(cat[CAT_FIELD.deskripsi]) : ''}</textarea>
            </div>
            <div class="flex justify-end gap-2 mt-5">
                <button onclick="closeCategoryModal()" class="px-4 py-2 rounded border">Batal</button>
                <button onclick="saveCategory(${isEdit ? Number(cat.id) : 'null'})" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Simpan</button>
            </div>
        </div>`;
    document.body.appendChild(modal);
}

function closeCategoryModal() {
    const modal = document.getElementById('category-modal');
    if (modal) modal.remove();
}

async function saveCategory(id) {
    const data = {
        [CAT_FIELD.nama]: document.getElementById('c-nama').value.trim(),
        [CAT_FIELD.deskripsi]: document.getElementById('c-deskripsi').value.trim()
    };

    if (!data[CAT_FIELD.nama]) {
        showToast('Nama kategori wajib diisi', 'error');
        return;
    }

    try {
        if (id) {
            await CategoryService.update(id, data);
            showToast('Kategori berhasil diperbarui');
        } else {
            await CategoryService.create(data);
            showToast('Kategori berhasil ditambahkan');
        }
        closeCategoryModal();
        renderCategories();
    } catch (err) {
        showToast(err.message, 'error');
    }
}

async function deleteCategory(id) {
    if (!confirm('Hapus kategori ini?')) return;
    try {
        await CategoryService.remove(id);
        showToast('Kategori berhasil dihapus');
        renderCategories();
    } catch (err) {
        showToast(err.message, 'error');
    }
}
