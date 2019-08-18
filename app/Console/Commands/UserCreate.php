<?php

namespace App\Console\Commands;

use App\Services\UserService;
use Illuminate\Console\Command;

class UserCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create
                                    {--name= : Name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создает пользователя';

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
     * @return mixed
     * @throws \Exception
     */
    public function handle(UserService $userService)
    {
        try {
            $user = $userService->create($this->option('name'));

            if ($user->exists) {
                return $this->info('USER ID: ' . $user->getKey());
            }
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }
}
