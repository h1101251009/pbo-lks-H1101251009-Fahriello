<?php

namespace App\Contracts;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function findAll(): array;

    public function findById(int $id): ?Category;

    public function create(Category $category): Category;

    public function update(int $id, Category $category): bool;

    public function delete(int $id): bool;
}
