<?php

use Illuminate\Database\Seeder;

class ChargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('charges')->insert(
            [
                'shipping' => 50,
                'discount' => 10,
                'vat' => 3,
                'tax' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
