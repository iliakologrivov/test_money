<?php

use Illuminate\Database\Seeder;
use \App\Helpers\AccountTypes;
use \App\Services\AccountService;
use \App\Services\UserService;
use \App\Helpers\Currencies;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param UserService $userService
     * @param AccountService $accountService
     * @return void
     * @throws Exception
     */
    public function run(UserService $userService, AccountService $accountService)
    {
        foreach ($userService->all() as $user) {
            try {
                $accountService->create($user, AccountTypes::ACCOUNT_TYPE_SENDING, Currencies::ACCOUNT_CURRENCY_USD);
            } catch (\Exception $exception) {
                echo $exception->getMessage() . PHP_EOL;
            }

            try {
                $accountService->create($user, AccountTypes::ACCOUNT_TYPE_RECEIVING, Currencies::ACCOUNT_CURRENCY_USD);
            } catch (\Exception $exception) {
                echo $exception->getMessage() . PHP_EOL;
            }
        }
    }
}
