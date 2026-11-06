<?php

namespace App\Repositories;

use App\Contracts\CategoryRepositoryInterface;
use App\Models\Category;
use PDO;

class MySQLCategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(private PDO $pdo) {}

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM categories ORDER BY nama');

        return array_map(
            fn(array $row) => $this->mapRow($row),
            $stmt->fetchAll()
        );
    }

    public function findById(int $id): ?Category
    {
        $stmt = $this->pdo->prepare('SELECT * FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? $this->mapRow($row) : null;
    }

    public function create(Category $category): Category
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO categories (nama, deskripsi) VALUES (:nama, :deskripsi)'
        );
        $stmt->execute([
            'nama'      => $category->getNama(),
            'deskripsi' => $category->getDeskripsi(),
        ]);

        return new Category(
            (int) $this->pdo->lastInsertId(),
            $category->getNama(),
            $category->getDeskripsi()
        );
    }

    public function update(int $id, Category $category): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE categories SET nama = :nama, deskripsi = :deskripsi WHERE id = :id'
        );

        return $stmt->execute([
            'id'        => $id,
            'nama'      => $category->getNama(),
            'deskripsi' => $category->getDeskripsi(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM categories WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }

    private function mapRow(array $row): Category
    {
        return new Category(
            (int) $row['id'],
            $row['nama'],
            $row['deskripsi']
        );
    }
}
