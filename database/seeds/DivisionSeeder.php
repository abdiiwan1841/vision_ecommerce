<?php

use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('divisions')->insert(array('name' => 'Chattagram','created_at' => now(),
        'updated_at' => now()));
        DB::table('divisions')->insert(array('name' => 'Rajshahi','created_at' => now(),
        'updated_at' => now()));
        DB::table('divisions')->insert(array('name' => 'Khulna','created_at' => now(),
        'updated_at' => now()));
        DB::table('divisions')->insert(array('name' => 'Barisal','created_at' => now(),
        'updated_at' => now()));
        DB::table('divisions')->insert(array('name' => 'Sylhet','created_at' => now(),
        'updated_at' => now()));
        DB::table('divisions')->insert(array('name' => 'Dhaka','created_at' => now(),
        'updated_at' => now()));
        DB::table('divisions')->insert(array('name' => 'Rangpur','created_at' => now(),
        'updated_at' => now()));
        DB::table('divisions')->insert(array('name' => 'Mymensingh','created_at' => now(),
        'updated_at' => now()));
    }
}
