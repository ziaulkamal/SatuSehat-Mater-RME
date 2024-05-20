<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\UserClientCredentialSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserClientCredentialSeeder::class);
        $this->call(AdminUserSeeder::class);
    }
}
