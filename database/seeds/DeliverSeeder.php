<?php

use Illuminate\Database\Seeder;

class DeliverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('deliveryinfos')->insert([
            'delay' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
