<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function register(array $data): User;
    public function findByEmail(string $email): ?User;
    public function revokeTokens(User $user): void;
}
