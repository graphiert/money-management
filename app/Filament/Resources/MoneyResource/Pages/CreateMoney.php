<?php

namespace App\Filament\Resources\MoneyResource\Pages;

use App\Filament\Resources\MoneyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMoney extends CreateRecord
{
    protected static string $resource = MoneyResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->user()->id;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
