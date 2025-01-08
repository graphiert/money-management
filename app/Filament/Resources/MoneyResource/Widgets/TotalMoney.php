<?php

namespace App\Filament\Resources\MoneyResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\TotalMoney as ShowMoney;

class TotalMoney extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(
                'Total money you have',
                ShowMoney::show(auth()->user()))
        ];
    }
}
