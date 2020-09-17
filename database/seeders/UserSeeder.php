<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed test admin
        $seededAdminEmail = 'admin@mat.ch';
        $user = User::where('email', '=', $seededAdminEmail)->first();
        if ($user === null) {
            $user = User::create([
                'scout_name' => 'Admin',
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'email' => $seededAdminEmail,
                'password' => Hash::make('password'),
            ]);
            $user->save();
        }

        // Seed test user
        $seededUserEmail = 'caspar.brenneisen@protonmail.ch';
        $user = User::where('email', '=', $seededUserEmail)->first();
        if ($user === null) {
            $user = User::create([
                'scout_name'                     => 'Vento',
                'first_name'                     => 'Caspar',
                'last_name'                      => 'Brenneisen',
                'email'                          => $seededUserEmail,
                'password'                       => Hash::make('password'),
            ]);
            $user->save();
        }
    }
}
