<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function create(array $data) : User
    {
        return User::create($data);
    }

    public function update(User $user, array $data) : bool
    {
        return $user->update($data);
    }

    public function delete(User $user) : bool
    {
        return (bool) $user->delete();
    }

    public function find(int $id): ?User
    {
        return User::findOrFail($id);
    }

    public function all()
    {
        return User::all();
    }
}
