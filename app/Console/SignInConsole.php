<?php

namespace App\Console;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\form;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;
use function Laravel\Prompts\password;
use function Laravel\Prompts\info;
use function Laravel\Prompts\error;

class SignInConsole {
    public static function authenticate() {
        info("Please authenticate yourself to continue. Don't have an account? Sign up your account on our website.");
        $type = select(
            label: 'Authenticate yourself first.',
            options: [
                'email' => "Using Email & Password",
                'byid' => "Using User ID",
            ]
        );
        $user_id = match ($type) {
            'email' => self::login(),
            'byid' => self::byId()
        };

        return $user_id;
    }

    public static function byId() {
        $response = form()
            ->text(
                label: "What is your User ID?",
                name: 'user_id',
                required: true,
                validate: function(string $value) {
                    if (!User::find($value)) {
                        return "User ID not found.";
                    }
                }
            )
            ->submit();
        return $response['user_id'];
    }

    public static function login() {
        $response = form()
            ->text(
                label: "What is your email address?",
                validate: ['email' => 'required|email'],
                name: 'email'
            )
            ->password(
                label: "What is your password?",
                validate: ['password' => 'required|min:8'],
                name: 'password'
            )
            ->submit();

        if(!Auth::attempt($response)) {
            error("These credentials don't match to our records.");
            self::login();
        };

        $userId = User::where('email', $response['email'])->get('id');

        return $userId[0]['id'];
    }

}

