<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DivisionSeeder::class);
        $this->call(DistrictSeeder::class);
        $this->call(AreaSeeder::class);
        // $this->call(UserSeeder::class);
        $this->call(AdminTableSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(MenuItemSeeder::class);
        $this->call(ChargeSeeder::class);
        $this->call(SubcategorySeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(AdvertisementSeeder::class);
        $this->call(DeliverSeeder::class);

                
    }
}
