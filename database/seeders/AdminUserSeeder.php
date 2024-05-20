<?php

namespace Database\Seeders;

use App\Models\AdministratorUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdministratorUser::create([
            'email'     => 'ziaulkamal1109@gmail.com',
            'password'  => Hash::make('Ganteng@123'),
            'condition' => false
        ]);
    }
}
