<?php

namespace App\Console\Commands;

use App\Services\AccountService;
use Illuminate\Console\Command;

class AccountList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Список аккаунтов';

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
     */
    public function handle(AccountService $accountService)
    {
        $accounts = $accountService->all()->map(function($row) {
            return [
                'id' => $row->id,
                'type' => $row->type,
                'currency' => $row->currency,
                'balance' => $row->balance,
            ];
        });

        $this->table(['ID', 'TYPE', 'CURRENCY' ,'BALANCE'], $accounts);
    }
}
