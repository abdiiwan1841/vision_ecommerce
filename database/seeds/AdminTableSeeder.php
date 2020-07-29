<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('admins')->insert(
        //     [
        //         'name' => 'Mr. admin',
        //         'adminname' => 'admin',
        //         'email' => 'info@example.com',
        //         'phone' => '01918009463',
        //         'image' => 'default.png',
        //         'password' => Hash::make('12345678')
        //     ]

        // );

        DB::table('admins')->insert(
            [
                'name' => 'Md Abdus Salam',
                'adminname' => 'salam',
                'email' => 'medicarebd09@gmail.com',
                'phone' => '01736402322',
                'image' => 'default.png',
                'password' => Hash::make('12345678')
            ]

        );
    }
}
