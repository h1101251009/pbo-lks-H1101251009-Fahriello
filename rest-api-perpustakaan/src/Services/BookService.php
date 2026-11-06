<?php

namespace App\Services;

use App\Contracts\BookRepositoryInterface;
use App\Models\Book;

class BookService
{
    public function __construct(private BookRepositoryInterface $repository) {}

    public function list(): array
    {
        return array_map(
            fn(Book $book) => $book->toArray(),
            $this->repository->findAll()
        );
    }

    public function get(int $id): array
    {
        $book = $this->repository->findById($id);

        if ($book === null) {
            throw new \Exception("Buku dengan ID $id tidak ditemukan", 404);
        }

        return $book->toArray();
    }

    public function create(array $data): array
    {
        $this->validate($data);

        $book = new Book(
            null,
            trim($data['judul']),
            trim($data['penulis']),
            (int) $data['tahun'],
            (int) $data['stok']
        );

        return $this->repository->create($book)->toArray();
    }

    public function update(int $id, array $data): array
    {
        $this->get($id);
        $this->validate($data);

        $book = new Book(
            $id,
            trim($data['judul']),
            trim($data['penulis']),
            (int) $data['tahun'],
            (int) $data['stok']
        );

        $this->repository->update($id, $book);

        return $book->toArray();
    }

    public function delete(int $id): void
    {
        $this->get($id);
        $this->repository->delete($id);
    }

    private function validate(array $data): void
    {
        if (empty(trim($data['judul'] ?? ''))) {
            throw new \Exception('Judul wajib diisi', 422);
        }

        if (empty(trim($data['penulis'] ?? ''))) {
            throw new \Exception('Penulis wajib diisi', 422);
        }

        $tahun = $data['tahun'] ?? null;
        if (!is_numeric($tahun) || $tahun < 1000 || $tahun > (int) date('Y') + 1) {
            throw new \Exception('Tahun tidak valid', 422);
        }

        $stok = $data['stok'] ?? null;
        if (!is_numeric($stok) || $stok < 0) {
            throw new \Exception('Stok tidak boleh negatif', 422);
        }
    }
}

