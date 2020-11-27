sdrxfc<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('activations')->truncate();
        DB::table('persistences')->truncate();
        DB::table('reminders')->truncate();
        DB::table('role_users')->truncate();
        DB::table('throttle')->truncate();
        DB::table('users')->truncate();

        $datas = [
            [
                'email' => 'admin@mail.com',
                'first_name' => 'Administrator',
                'last_name' => '',
                'gender' => 'Male',
                'role' => 'super-admin',
                'address' => 'Jakarta',
                'phone'   => '622157905788',
                'status' => 1
            ],
        ];

        foreach ( $datas as $key => $data ) {
            $user = Sentinel::registerAndActivate( [
                'email' => $data[ 'email' ],
                'password' => "password",
                'first_name' => $data[ 'first_name' ],
                'last_name' => $data[ 'last_name' ],
                'gender' => $data[ 'gender' ],
                'phone' => $data[ 'phone' ],
                'address' => $data[ 'address' ],
                'status' => 1
            ] );
            Sentinel::findRoleBySlug( $data[ 'role' ] )->users()->attach( $user );
        }

        Schema::enableForeignKeyConstraints();
    }
}
