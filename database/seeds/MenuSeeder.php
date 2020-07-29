<?php

use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('admin_menus')->insert(
            [
                'name' => 'footer_second_column',
            ]
        );


    }
}
