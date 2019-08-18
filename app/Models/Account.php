<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Account
 * @package App\Models
 * @property int user_id
 * @property string type
 * @property string currency
 * @property int balance
 * @property Carbon updated_at
 * @property Carbon created_at
 */
class Account extends Model
{
    protected $table = 'accounts';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'type',
        'currency'
    ];
}
