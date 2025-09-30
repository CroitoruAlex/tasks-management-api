<?php

namespace App\Repositories\Interfaces;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProjectRepositoryInterface
{
    public function getPaginated(array $filters = []): LengthAwarePaginator;

    public function findById(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id): bool;
}
