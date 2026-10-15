<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class StockWiseUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@stockwise.test'],
            [
                'name' => 'Administrator',
                'password' => 'Admin123!',
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@stockwise.test'],
            [
                'name' => 'Staff Gudang',
                'password' => 'Staff123!',
                'role' => 'staff',
            ]
        );
    }
}
