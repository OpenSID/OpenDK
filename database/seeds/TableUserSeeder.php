<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class TableUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //cek user
        $adminUser = [
            'email'     => 'admin@mail.com',
            'name'      => 'Administrator',
            'gender'    => 'Male',
            'role'      => 'super-admin',
            'address'   => 'Jakarta',
            'phone'     => '622157905788',
            'status'    => 1,
            'password'  => bcrypt('password'),
        ];
        if (!User::where('email', $adminUser['email'])->exists()) {
            User::create($adminUser);
        }
    }
}
