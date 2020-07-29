<?php

use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_menu_items')->insert(
            [
                'label' => 'home',
                'link' => 'http://localhost/ecommerce',
                'parent' => 0,
                'sort' => 0,
                'menu' => 1,
                'depth' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('admin_menu_items')->insert(
            [
                'label' => 'Shop',
                'link' => 'http://localhost/ecommerce/shop',
                'parent' => 0,
                'sort' => 1,
                'menu' => 1,
                'depth' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('admin_menu_items')->insert(
            [
                'label' => 'About',
                'link' => '#',
                'parent' => 0,
                'sort' => 0,
                'menu' => 2,
                'depth' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('admin_menu_items')->insert(
            [
                'label' => 'About',
                'link' => '#',
                'parent' => 0,
                'sort' => 0,
                'menu' => 3,
                'depth' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
