<?php

namespace App\Console\Commands;

use App\Models\User;
use App\TotalMoney;
use App\Console\SignInConsole;
use Illuminate\Console\Command;
use function Laravel\Prompts\form;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\info;

class AddMoney extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'money:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add income or money out.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        info('Welcome to money-management CLI.');
        $user = User::find(SignInConsole::authenticate());
        info("Hello, $user->name.");

        $response = form()
            ->select(
                label: "What type this is?",
                options: [
                    "Pengeluaran" => "Money Out",
                    "Pemasukan" => "Income"
                ],
                required: true,
                name: 'type'
            )
            ->text(
                label: "What you call this?",
                validate: ['title' => 'required|min:3|max:255'],
                name: 'title'
            )
            ->text(
                label: "How much is it?",
                validate: ['amount' => 'required|numeric'],
                name: 'amount'
            )
            ->textarea(
                label: "Any explanation?",
                name: 'description'
            )
            ->submit();

        $user->money()->create([
            'title' => $response['title'],
            'type' => $response['type'],
            'amount' => $response['amount'],
            'description' => $response['description']
        ]);

        outro('Your record successfully added. Your money now '. TotalMoney::show($user) . '.');
    }
}
