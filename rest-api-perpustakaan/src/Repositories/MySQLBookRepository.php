<?php

namespace App\Repositories;

use App\Contracts\BookRepositoryInterface;
use App\Models\Book;
use PDO;

class MySQLBookRepository implements BookRepositoryInterface
{
    public function __construct(private PDO $pdo) {}

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM books ORDER BY judul');

        return array_map(
            fn(array $row) => $this->mapRow($row),
            $stmt->fetchAll()
        );
    }

    public function findById(int $id): ?Book
    {
        $stmt = $this->pdo->prepare('SELECT * FROM books WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? $this->mapRow($row) : null;
    }

    public function create(Book $book): Book
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO books (judul, penulis, tahun, stok)
             VALUES (:judul, :penulis, :tahun, :stok)'
        );
        $stmt->execute([
            'judul'   => $book->getJudul(),
            'penulis' => $book->getPenulis(),
            'tahun'   => $book->getTahun(),
            'stok'    => $book->getStok(),
        ]);

        return new Book(
            (int) $this->pdo->lastInsertId(),
            $book->getJudul(),
            $book->getPenulis(),
            $book->getTahun(),
            $book->getStok()
        );
    }

    public function update(int $id, Book $book): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE books
             SET judul = :judul, penulis = :penulis, tahun = :tahun, stok = :stok
             WHERE id = :id'
        );

        return $stmt->execute([
            'id'      => $id,
            'judul'   => $book->getJudul(),
            'penulis' => $book->getPenulis(),
            'tahun'   => $book->getTahun(),
            'stok'    => $book->getStok(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM books WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }

    private function mapRow(array $row): Book
    {
        return new Book(
            (int) $row['id'],
            $row['judul'],
            $row['penulis'],
            (int) $row['tahun'],
            (int) $row['stok']
        );
    }
}
