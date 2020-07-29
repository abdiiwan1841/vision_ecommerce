<?php

use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sizes')->insert(
            [
                'name' => 'M',
                'type' => 'ecom',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('sizes')->insert(
            [
                'name' => 'XL',
                'type' => 'ecom',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('sizes')->insert(
            [
                'name' => 'XXL',
                'type' => 'ecom',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );


        DB::table('sizes')->insert(
            [
                'name' => '150 gm',
                'type' => 'pos',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('sizes')->insert(
            [
                'name' => '250 gm',
                'type' => 'pos',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('sizes')->insert(
            [
                'name' => '100 ml',
                'type' => 'pos',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('sizes')->insert(
            [
                'name' => '1 ltr',
                'type' => 'pos',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );


    }
}
