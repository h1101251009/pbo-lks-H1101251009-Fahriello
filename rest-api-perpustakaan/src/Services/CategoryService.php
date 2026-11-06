<?php

namespace App\Services;

use App\Contracts\CategoryRepositoryInterface;
use App\Models\Category;
use PDOException;

class CategoryService
{
    public function __construct(private CategoryRepositoryInterface $repository) {}

    public function list(): array
    {
        return array_map(
            fn(Category $c) => $c->toArray(),
            $this->repository->findAll()
        );
    }

    public function get(int $id): array
    {
        return $this->findOrFail($id)->toArray();
    }

    public function create(array $data): array
    {
        $category = $this->buildCategory(null, $data);

        try {
            return $this->repository->create($category)->toArray();
        } catch (PDOException $e) {
            throw $this->mapDuplicate($e);
        }
    }

    public function update(int $id, array $data): array
    {
        $this->findOrFail($id);
        $category = $this->buildCategory($id, $data);

        try {
            $this->repository->update($id, $category);
        } catch (PDOException $e) {
            throw $this->mapDuplicate($e);
        }

        return $this->findOrFail($id)->toArray();
    }

    public function delete(int $id): void
    {
        $this->findOrFail($id);
        $this->repository->delete($id);
    }

    private function findOrFail(int $id): Category
    {
        $category = $this->repository->findById($id);

        if ($category === null) {
            throw new \Exception('Kategori tidak ditemukan', 404);
        }

        return $category;
    }

    private function buildCategory(?int $id, array $data): Category
    {
        $nama = trim((string) ($data['nama'] ?? ''));
        $deskripsi = isset($data['deskripsi']) ? trim((string) $data['deskripsi']) : null;

        if ($nama === '') {
            throw new \Exception('Nama kategori wajib diisi', 422);
        }

        if (mb_strlen($nama) > 100) {
            throw new \Exception('Nama kategori maksimal 100 karakter', 422);
        }

        return new Category($id, $nama, $deskripsi);
    }

    private function mapDuplicate(PDOException $e): \Throwable
    {
        // 1062 = Duplicate entry (kolom nama bersifat UNIQUE)
        if ((int) ($e->errorInfo[1] ?? 0) === 1062) {
            return new \Exception('Nama kategori sudah digunakan', 409);
        }

        return $e;
    }
}
