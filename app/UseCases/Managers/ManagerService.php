<?php

namespace App\UseCases\Managers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Collection;

final readonly class ManagerService
{
    public function getList(): Collection
    {
        return User::where('role', UserRole::SupportManager)
            ->orderBy('name')
            ->get(['id', 'name']);
    }
}
