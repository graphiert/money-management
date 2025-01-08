<?php

namespace App;

use App\Models\User;
use Illuminate\Support\Number;

class TotalMoney
{
    public static function show(User $user)
    {
        $total = 0;
        $sets = $user->money()->get();
        foreach ($sets as $a) {
            if ($a->type == "Pemasukan") {
                $total += $a->amount;
            } else {
                $total -= $a->amount;
            }
        };
        return Number::currency($total, 'IDR', 'id');
    }
}
