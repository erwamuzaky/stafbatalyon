<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Seed the first admin account.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@stafbatalyon.test'],
            [
                'name' => 'Admin Staf Batalyon',
                'password' => 'Admin12345',
                'role' => 'admin',
                'status' => 'approved',
                'email_verified_at' => now(),
            ],
        );

        $this->command->info('Akun admin: admin@stafbatalyon.test / Admin12345');
    }
}
