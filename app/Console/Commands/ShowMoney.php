<?php

namespace App\Console\Commands;

use App\TotalMoney;
use App\Models\User;

use Illuminate\Console\Command;
use App\Console\SignInConsole;
use function Laravel\Prompts\info;
use function Laravel\Prompts\outro;


class ShowMoney extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'money:show';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show total money.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info('Welcome to money-management CLI.');
        $user = User::find(SignInConsole::authenticate());
        info("Hello, $user->name.");
        outro("Your money now ". TotalMoney::show($user));
    }
}
