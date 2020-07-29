<?php

use Illuminate\Database\Seeder;

class AdvertisementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('advertisements')->insert(
            [
                'title' => 'test advertisements',
                'image' => 'ad.jpg',
                'button_text' => 'Discover More',
                'button_link' => '#',
                'created_at' => now(),
                'updated_at' => now(),
            ]

        );
    }
}
