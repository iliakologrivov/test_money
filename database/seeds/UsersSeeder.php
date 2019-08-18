<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param \App\Services\UserService $userService
     * @return void
     * @throws Exception
     */
    public function run(\App\Services\UserService $userService)
    {
        try {
            $userService->create('user_1');
        } catch (\Exception $exception) {
            echo $exception->getMessage() . PHP_EOL;
        }

        try {
            $userService->create('user_2');
        } catch (\Exception $exception) {
            echo $exception->getMessage() . PHP_EOL;
        }
    }
}
