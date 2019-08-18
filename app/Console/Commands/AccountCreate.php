<?php

namespace App\Console\Commands;

use App\Services\AccountService;
use App\Services\UserService;
use Illuminate\Console\Command;

class AccountCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:create {--user_id= : ID пользователя}
                                            {--type= : Тип аккаунта (sending или receiving)}
                                            {--currency= : Валюта аккаунта (USD, EUR, GBP, RON)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создаст аккаунт пользователю';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param UserService $userService
     * @param AccountService $accountService
     * @return mixed
     */
    public function handle(UserService $userService, AccountService $accountService)
    {
        try {
            $user = $userService->find($this->option('user_id'));

            $account = $accountService->create($user, $this->option('type'), $this->option('currency'));

            if ($account->exists) {
                return $this->info('ACCOUNT ID: ' . $account->getKey());
            }
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }
}
