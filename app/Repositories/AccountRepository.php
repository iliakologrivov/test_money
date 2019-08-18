<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\User;

class AccountRepository
{
    public const ACCOUNT_TYPE_SENDING = 'sending';
    public const ACCOUNT_TYPE_RECEIVING = 'receiving';

    public const ACCOUNT_CURRENCY_USD = 'USD';
    public const ACCOUNT_CURRENCY_EUR = 'EUR';
    public const ACCOUNT_CURRENCY_GBP = 'GBP';
    public const ACCOUNT_CURRENCY_RON = 'RON';

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
            self::ACCOUNT_TYPE_SENDING,
            self::ACCOUNT_TYPE_RECEIVING
        ];
    }

    public function getCurrencies(): array
    {
        return [
            self::ACCOUNT_CURRENCY_USD,
            self::ACCOUNT_CURRENCY_EUR,
            self::ACCOUNT_CURRENCY_GBP,
            self::ACCOUNT_CURRENCY_RON,
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

    public function transaction(Account $from, float $countFrom, Account $to, float $countTo)
    {
        try {
            \DB::beginTransaction();

            $updateFrom = \DB::table('accounts')
                ->where('id', '=', $from->getKey())
                ->where('updated_at', '=', $from->updated_at)
                ->update([
                    'balance' => \DB::raw('`balance` - ' . $countFrom),
                ]);

            $updateTo = \DB::table('accounts')
                ->where('id', '=', $to->getKey())
                ->where('updated_at', '=', $to->updated_at)
                ->update([
                    'balance' => \DB::raw('`balance` + ' . $countTo),
                ]);


            if ($updateFrom + $updateTo == 2) {
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
}
