<?php

namespace App\Console\Commands;

use App\Services\AccountService;
use Illuminate\Console\Command;

class MoneySend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'money:send {--from= : Аккаунт отправителя}
                                        {--to= : Аккаунт получателя}
                                        {--count= : Кол-во для списания}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправить деньги со счета на счет';

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
     * @param AccountService $accountService
     * @return mixed
     * @throws \Exception
     */
    public function handle(AccountService $accountService)
    {
        try {
            $result = $accountService->transaction($this->option('from'), $this->option('to'), $this->option('count'));

            if (true == $result) {
                return $this->info('OK');
            }

            return $this->error('Произошла ошибка!');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }
}
