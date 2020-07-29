<?php

use Illuminate\Database\Seeder;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subcategories')->insert(
            [
                'subcategory_name' => 'Shoes',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('subcategories')->insert(
            [
                'subcategory_name' => 'Sandal',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('subcategories')->insert(
            [
                'subcategory_name' => 'Handbag',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('subcategories')->insert(
            [
                'subcategory_name' => 'Shirts',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('subcategories')->insert(
            [
                'subcategory_name' => 'Pants',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('subcategories')->insert(
            [
                'subcategory_name' => 'lorem',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
