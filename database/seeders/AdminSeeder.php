<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'is_admin' => 1,
            'email' => 'admin@buckhill.co.uk',
            'password' => bcrypt('password'),
        ]);
    }
}
