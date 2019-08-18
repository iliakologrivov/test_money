<?php

use Illuminate\Database\Seeder;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param \App\Services\UserService $userService
     * @param \App\Services\AccountService $accountService
     * @return void
     * @throws Exception
     */
    public function run(\App\Services\UserService $userService, \App\Services\AccountService $accountService)
    {
        foreach ($userService->all() as $user) {
            try {
                $accountService->create($user, 'sending', 'USD');
            } catch (\Exception $exception) {
                echo $exception->getMessage() . PHP_EOL;
            }

            try {
                $accountService->create($user, 'receiving', 'USD');
            } catch (\Exception $exception) {
                echo $exception->getMessage() . PHP_EOL;
            }
        }
    }
}
