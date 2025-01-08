<?php

namespace App\Console\Commands;

use App\Console\SignInConsole;
use App\TotalMoney;
use App\Models\Money;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Number;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\form;
use function Laravel\Prompts\info;
use function Laravel\Prompts\search;
use function Laravel\Prompts\select;
use function Laravel\Prompts\table;
use function Laravel\Prompts\outro;

class IdentifyMoney extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'money:identify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info('Welcome to money-management CLI.');
        $user = User::find(SignInConsole::authenticate());
        info("Hello, $user->name.");

        $id = search(
            label: "Select a record...",
            options: fn (string $value) => strlen($value) > 0
                ? $user->money()->where('title', 'like', "%{$value}%")->pluck('title', 'id')->all()
                : []
        );

        $action = select(
            label: "What will you do with this record?",
            options: [
                'read' => 'See this record.',
                'update' => 'Edit this record.',
                'delete' => 'Delete this record.'
            ]
        );

        $record = $user->money()->find($id);

        match($action) {
            'read' => table(
                headers: ['#', '&'],
                rows: [
                    ["Title", $record->title],
                    ["Description", $record->description == "" ? "No description provided." : $record->description],
                    ["Type", $record->type == "Pemasukan" ? "Income" : "Money Out"],
                    ["Amount", Number::currency($record->amount, 'IDR', 'id')],
                    ["Created at", date_format(date_create($record->created_at), "m/d H:i")]
                ]
            ),
            'update' => $record->update(
                form()
                ->select(
                    label: "What type this is?",
                    options: [
                        "Pengeluaran" => "Money Out",
                        "Pemasukan" => "Income"
                    ],
                    required: true,
                    name: 'type',
                    default: $record->type
                )
                ->text(
                    label: "What you call this?",
                    validate: ['title' => 'required|min:3|max:255'],
                    name: 'title',
                    default: $record->title
                )
                ->text(
                    label: "How much is it?",
                    validate: ['amount' => 'required|numeric'],
                    name: 'amount',
                    default: $record->amount
                )
                ->textarea(
                    label: "Any explanation?",
                    name: 'description',
                    default: $record->description ??= ""
                )
                ->submit()
            ),
            'delete' => confirm(
                label: "Are you sure to comtinue?",
                hint: "This action cannot be undone."
            ) ? $record->delete() : null
        };
        outro("Your money now ". TotalMoney::show($user));
    }
}
