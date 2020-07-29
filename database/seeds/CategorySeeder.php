<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert(
            [
                'category_name' => 'Men',
                'image' => 'kids-2020-04-27.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('categories')->insert(
            [
                'category_name' => 'Women',
                'image' => 'kids-2020-04-27.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('categories')->insert(
            [
                'category_name' => 'Kids',
                'image' => 'kids-2020-04-27.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
