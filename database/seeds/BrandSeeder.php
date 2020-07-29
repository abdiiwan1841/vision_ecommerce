<?php

use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('brands')->insert([
            'brand_name' => 'Nike',
            'image' => 'lorem-2020-04-14',
            'created_at' => now(),
            'updated_at' => now(),

        ]);
    }
}
