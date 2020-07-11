<?php

namespace App;

use KD\Wallet\Models\Wallet as KDWallet;

use Jamesh\Uuid\HasUuid;

class Wallet extends KDWallet
{
    use HasUuid;
}
