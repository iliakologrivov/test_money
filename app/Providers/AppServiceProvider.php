<?php

namespace App\Providers;

use App\Repositories\AccountRepository;
use App\Repositories\UserRepository;
use App\Services\AccountService;
use App\Services\ExchangeService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(UserService::class, function () {
            return new UserService(new UserRepository());
        });

        $this->app->singleton(AccountService::class, function () {
            return new AccountService(new AccountRepository(), app(ExchangeService::class));
        });

        $this->app->singleton(ExchangeService::class, function () {
            return new ExchangeService(new \GuzzleHttp\Client());
        });

        \DB::enableQueryLog();
    }
}
