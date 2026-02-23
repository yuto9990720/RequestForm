<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Validation\Rules\Password;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input)
{
    Validator::make($input, [
        'name' => ['required', 'string',],
        'email' => ['required', 'email', ],
        'password' => ['required', Password::defaults()],
    ], [
        'name.required' => 'お名前を入力してください',
        'email.required' => 'メールアドレスを入力してください',
        'email.email' => 'メール形式で入力してください',
        'password.required' => 'パスワードを入力してください',
    ])->validate();

    return User::create([
        'name' => $input['name'],
        'email' => $input['email'],
        'password' => Hash::make($input['password']),
    ]);
}
}