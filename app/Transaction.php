<?php

namespace App;

use KD\Wallet\Models\Transaction as KDWalletTransaction;

use Jamesh\Uuid\HasUuid;

class Transaction extends KDWalletTransaction
{
    use HasUuid;
}
