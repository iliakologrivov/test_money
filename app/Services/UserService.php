<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create(string $name)
    {
        if (empty($name)) {
            throw new \Exception('Имя пользователя не может быть пустым!');
        }

        try {
            return $this->userRepository->create([
                'name' => $name
            ]);
        } catch (\Illuminate\Database\QueryException $exception) {
            if (mb_stripos($exception->getMessage(), 'duplicate entry') !== false) {
                throw new \Exception('Пользователь с таким иминем уже существует!');
            }

            throw $exception;
        }
    }

    public function find(int $id)
    {
        try {
            return $this->userRepository->find($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            throw new \Exception('Пользователь не найден!');
        }
    }

    public function all()
    {
        return $this->userRepository->all();
    }
}