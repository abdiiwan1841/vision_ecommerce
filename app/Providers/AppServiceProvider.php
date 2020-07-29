<?php

namespace App\Providers;


use App\Company;
use App\Product;
use App\Category;
use App\Headertop;
use App\Subcategory;
use Harimayco\Menu\Facades\Menu;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        View::composer('*', function ($view) {
            $product_categories = Category::take(10)->get();
            $product_subcategories = Subcategory::take(10)->get();
            $footer_second_column_menu = Menu::getByName('footer_second_column');
            $footer_third_column_menu = Menu::getByName('footer_third_column');
            $CompanyInfo = Company::first();
            $social =  json_decode($CompanyInfo->social, true);
            $view->with('footer_second_column_menu', $footer_second_column_menu);
            $view->with('footer_third_column_menu', $footer_third_column_menu);
            $view->with('social', $social);
            $view->with('product_categories', $product_categories);
            $view->with('product_subcategories', $product_subcategories);
            $view->with('CompanyInfo', $CompanyInfo);

        });

        Schema::defaultStringLength(191);
    }
}
