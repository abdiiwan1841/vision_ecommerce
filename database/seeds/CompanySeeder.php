<?php

use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            'map_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14609.11163560886!2d90.3867914!3d23.7374672!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xf16a47b763f2798f!2sVision+Cosmetics+Limited!5e0!3m2!1sen!2sbd!4v1510481752841" width="100%" height="500" frameborder="0" style="border:0" allowfullscreen></iframe>',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
