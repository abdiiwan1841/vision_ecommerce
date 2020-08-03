<?php

namespace App\Providers;


use App\Page;
use App\Company;
use App\Product;
use App\Category;
use App\Headertop;
use App\Subcategory;
use App\GeneralOption;
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
            $page_list = Page::take(10)->latest()->get();
            $g_opt = GeneralOption::first();
            $g_opt_value = json_decode($g_opt->options, true);
            $product_categories = Category::take(10)->get();
            $product_subcategories = Subcategory::take(10)->get();
            $footer_second_column_menu = Menu::getByName('footer_second_column');
            $CompanyInfo = Company::first();
            $social =  json_decode($CompanyInfo->social, true);
            $view->with('page_list', $page_list);
            $view->with('g_opt_value', $g_opt_value);
            $view->with('footer_second_column_menu', $footer_second_column_menu);
            $view->with('social', $social);
            $view->with('product_categories', $product_categories);
            $view->with('product_subcategories', $product_subcategories);
            $view->with('CompanyInfo', $CompanyInfo);

        });

        Schema::defaultStringLength(191);
    }
}
