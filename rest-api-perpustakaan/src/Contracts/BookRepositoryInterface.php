<?php

namespace App\Contracts;

use App\Models\Book;

interface BookRepositoryInterface
{
    public function findAll(): array;

    public function findById(int $id): ?Book;

    public function create(Book $book): Book;

    public function update(int $id, Book $book): bool;

    public function delete(int $id): bool;
}
