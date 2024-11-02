<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MoneyResource\Pages;
use App\Filament\Resources\MoneyResource\RelationManagers;
use App\Models\Money;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MoneyResource extends Resource
{
    protected static ?string $model = Money::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function getWidgets(): array
    {
        return [
            MoneyResource\Widgets\TotalMoney::class
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->required()
                    ->options([
                        "Pemasukan" => "Pemasukan",
                        "Pengeluaran" => "Pengeluaran"
                    ]),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR', locale: 'id')
                    ->color(function (string $state, Money $record) {
                        if($record->type == "Pemasukan") {
                            return 'success';
                        } else {
                            return 'danger';
                        }
                    })
                    ->icon(function (string $state, Money $record) {
                        if($record->type == "Pemasukan") {
                            return 'heroicon-o-plus';
                        } else {
                            return 'heroicon-o-minus';
                        }
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('user_id', auth()->user()->id);
            })
            ->filters([
               //
            ])
            ->actions([
               Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
               Tables\Actions\BulkActionGroup::make([
                   Tables\Actions\DeleteBulkAction::make(),
               ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMoney::route('/'),
            'create' => Pages\CreateMoney::route('/create'),
            'edit' => Pages\EditMoney::route('/{record}/edit'),
        ];
    }
}
