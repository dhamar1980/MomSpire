<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Pengguna;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $phoneNumber = $input['phone_number'] ?? null;
        $hashedPassword = Hash::make($input['password']);

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'no_telp' => $phoneNumber,
            'password' => $hashedPassword,
            'role' => 'pengguna',
            'has_custom_password' => true,
        ]);

        DB::table('pengguna')->insert([
            'id' => $user->id,
            'user_id' => $user->id,
            'name' => $input['name'],
            'email' => $input['email'],
            'no_telp' => $phoneNumber,
            'password' => $hashedPassword,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $user;
    }
}
