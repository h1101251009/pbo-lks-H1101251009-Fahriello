class ApiClient {
    constructor(baseUrl) {
        this.baseUrl = baseUrl;
    }

    async request(path, options = {}) {
        const response = await fetch(this.baseUrl + path, {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            ...options
        });

        const data = await response.json().catch(() => null);

        if (!response.ok) {
            const message = (data && data.message) || 'Terjadi kesalahan (' + response.status + ')';
            throw new Error(message);
        }
        return data;
    }

    get(path) {
        return this.request(path);
    }

    post(path, body) {
        return this.request(path, { method: 'POST', body: JSON.stringify(body) });
    }

    put(path, body) {
        return this.request(path, { method: 'PUT', body: JSON.stringify(body) });
    }

    delete(path) {
        return this.request(path, { method: 'DELETE' });
    }
}

const api = new ApiClient(CONFIG.BASE_URL);

const BookService = {
    getAll: () => api.get('/books'),
    getById: (id) => api.get('/books/' + id),
    create: (data) => api.post('/books', data),
    update: (id, data) => api.put('/books/' + id, data),
    remove: (id) => api.delete('/books/' + id)
};

const CategoryService = {
    getAll: () => api.get('/categories'),
    getById: (id) => api.get('/categories/' + id),
    create: (data) => api.post('/categories', data),
    update: (id, data) => api.put('/categories/' + id, data),
    remove: (id) => api.delete('/categories/' + id)
};
