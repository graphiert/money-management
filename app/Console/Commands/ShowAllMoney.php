<?php

namespace App\Console\Commands;

use App\Console\SignInConsole;
use App\TotalMoney;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Number;
use function Laravel\Prompts\info;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\table;

class ShowAllMoney extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'money:show-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show all money usage.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info('Welcome to money-management CLI.');
        $user = User::find(SignInConsole::authenticate());
        info("Hello, $user->name.");
        $table = [];
        foreach(
            $user->money()->get(['title', 'type', 'amount', 'created_at'])->toArray() as $data
        ) {
            $table[] = [
                $data['title'],
                $data['type'] == "Pemasukan" ? 'Income' : 'Money Out',
                Number::currency($data['amount'], 'IDR', 'id'),
                date_format(date_create($data['created_at']), "m/d H:i")
            ];
        }
        table(
            headers: ['Title', 'Type', 'Amount', 'Created At'],
            rows: $table
        );
        outro("Your money now ". TotalMoney::show($user));
    }
}
