<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\CategoryService;

class CategoryController
{
    public function __construct(
        private CategoryService $service,
        private Request $request
    ) {}

    public function index(): void
    {
        $this->respond(fn() => Response::json([
            'success' => true,
            'message' => 'Daftar kategori',
            'data'    => $this->service->list(),
        ]));
    }

    public function show(string $id): void
    {
        $this->respond(fn() => Response::json([
            'success' => true,
            'message' => 'Detail kategori',
            'data'    => $this->service->get((int) $id),
        ]));
    }

    public function store(): void
    {
        $this->respond(fn() => Response::json([
            'success' => true,
            'message' => 'Kategori berhasil dibuat',
            'data'    => $this->service->create($this->request->getBody()),
        ], 201));
    }

    public function update(string $id): void
    {
        $this->respond(fn() => Response::json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui',
            'data'    => $this->service->update((int) $id, $this->request->getBody()),
        ]));
    }

    public function destroy(string $id): void
    {
        $this->respond(function () use ($id) {
            $this->service->delete((int) $id);
            Response::json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus',
            ]);
        });
    }

    private function respond(callable $action): void
    {
        try {
            $action();
        } catch (\Exception $e) {
            $code = $e->getCode();
            $status = (is_int($code) && $code >= 400 && $code < 600) ? $code : 500;

            Response::json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $status);
        }
    }
}
