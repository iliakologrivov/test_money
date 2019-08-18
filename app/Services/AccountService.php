<?php

namespace App\Services;

use App\Models\Account;
use App\Models\User;
use App\Repositories\AccountRepository;

class AccountService
{
    protected $accountRepository;

    protected $exchangeService;

    public function __construct(AccountRepository $accountRepository, ExchangeService $exchangeService)
    {
        $this->accountRepository = $accountRepository;
        $this->exchangeService = $exchangeService;
    }

    public function create(User $user, string $type, string $currency): Account
    {
        if (! in_array($type, $this->accountRepository->getTypes())) {
            throw new \Exception('Неверный тип аккаунта!');
        }

        if (! in_array($currency, $this->accountRepository->getCurrencies())) {
            throw new \Exception('Неверная валюта аккаунта!');
        }

        try {
            return $this->accountRepository->create($user, $type, $currency);
        } catch (\Illuminate\Database\QueryException $exception) {
            if (mb_stripos($exception->getMessage(), 'duplicate entry') !== false) {
                throw new \Exception('Аккаунт уже существует!');
            }

            throw $exception;
        }
    }

    public function find(int $id)
    {
        try {
            return $this->accountRepository->find($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            throw new \Exception('Аккаунт не найден!');
        }
    }

    public function all()
    {
        return $this->accountRepository->all();
    }

    public function getLocked(int $id)
    {
        return $this->accountRepository->getLocked($id);
    }

    public function transaction($from, $to, $count)
    {
        $fromAccount = $this->accountRepository->getLocked($from);

        if ($fromAccount->type != $this->accountRepository::ACCOUNT_TYPE_SENDING) {
            throw new \Exception('Неверный тип аккаунта списания!');
        }

        $toAccount = $this->accountRepository->getLocked($to);

        if ($toAccount->type != $this->accountRepository::ACCOUNT_TYPE_RECEIVING) {
            throw new \Exception('Неверный тип аккаунта получения!');
        }

        if ($count <= 0.0) {
            throw new \Exception('Сумма меньше минимума!');
        }

        if (($fromAccount->balance - $count) < 0.0) {
            throw new \Exception('Недостаточно средств!');
        }

        if ($fromAccount->currency == $toAccount->currency) {
            return $this->accountRepository->transaction($fromAccount, $count, $toAccount, $count);
        }

        $countTo = $this->exchangeService->exchange($fromAccount->currency, $toAccount->currency, $count);

        return $this->accountRepository->transaction($fromAccount, $count, $toAccount, $countTo);
    }
}