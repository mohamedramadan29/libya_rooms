<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->create([
            'name'=>'admin',
            'email'=>'admin@email.com',
            'phone'=>'01011642731',
            'password'=>Hash::make('11111111'),
            'type'=>'admin',
            'status'=>1,
        ]);
    }
}
