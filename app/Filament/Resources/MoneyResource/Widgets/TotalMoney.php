<?php

namespace App\Filament\Resources\MoneyResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class TotalMoney extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total money you have', function () {
                $total = 0;
                $sets = auth()->user()->money()->get();
                foreach ($sets as $a) {
                    if ($a->type == "Pemasukan") {
                        $total += $a->amount;
                    } else {
                        $total -= $a->amount;
                    }
                };
                return Number::currency($total, 'IDR', 'id');
            })
        ];
    }
}
