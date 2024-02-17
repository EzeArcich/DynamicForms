<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::create([
            'name' => 'Test User Admin',
            'email' => 'test@example.com',
            'password' => bcrypt('12345678'),

        ]);

        $readerUser = User::create([
            'name' => 'Test User Reader',
            'email' => 'test2@example.com',
            'password' => bcrypt('12345678'),
        ]);

        
        $adminUser->assignRole('admin');
        $readerUser->assignRole('reader');
    }
}
