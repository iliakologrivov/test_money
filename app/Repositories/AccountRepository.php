<?php

namespace App\Repositories;

use App\Helpers\AccountTypes;
use App\Helpers\Currencies;
use App\Models\Account;
use App\Models\User;

class AccountRepository
{
    public function create(User $user, string $type, string $currency): Account
    {
        return Account::create([
            'user_id' => $user->getKey(),
            'type' => $type,
            'currency' => $currency,
        ]);
    }

    public function delete(Account $account): bool
    {
        return (bool) $account->delete();
    }

    public function find(int $id): Account
    {
        return Account::findOrFail($id);
    }

    public function all()
    {
        return Account::all();
    }

    public function getTypes(): array
    {
        return [
            AccountTypes::ACCOUNT_TYPE_SENDING,
            AccountTypes::ACCOUNT_TYPE_RECEIVING
        ];
    }

    public function getCurrencies(): array
    {
        return [
            Currencies::ACCOUNT_CURRENCY_USD,
            Currencies::ACCOUNT_CURRENCY_EUR,
            Currencies::ACCOUNT_CURRENCY_GBP,
            Currencies::ACCOUNT_CURRENCY_RON,
            Currencies::ACCOUNT_CURRENCY_COP,
        ];
    }

    public function getLocked(int $id)
    {
        $account = Account::where('id', '=', $id)
            ->lockForUpdate()
            ->firstOrFail();

        if (! is_null($account)) {
            return $account;
        }

        throw new \Exception('Аккаунт не найден!');
    }

    public function transaction(\Closure $function)
    {
        try {
            \DB::beginTransaction();

            if ($function()) {
                \DB::commit();

                return true;
            }

            \DB::rollback();

            return false;
        } catch (\Exception $exception) {
            \DB::rollback();

            throw $exception;
        }
    }

    public function increaseBalanceForAccount(Account $account, float $count): bool
    {
        return \DB::table('accounts')
            ->where('id', '=', $account->getKey())
            ->where('updated_at', '=', $account->updated_at)
            ->update([
                'balance' => \DB::raw('`balance` + ' . $count),
            ]) == 1;
    }

    public function decreaseBalanceForAccount(Account $account, float $count): bool
    {
        return \DB::table('accounts')
            ->where('id', '=', $account->getKey())
            ->where('updated_at', '=', $account->updated_at)
            ->update([
                'balance' => \DB::raw('`balance` - ' . $count),
            ]) == 1;
    }
}
