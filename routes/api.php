<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('district/{id}', 'Api\ApiInformationController@districtinfo');
Route::get('area/{id}', 'Api\ApiInformationController@areainfo');
Route::get('userinfo/{id}', 'Api\ApiInformationController@userinfo');
Route::get('supplierinfo/{id}', 'Api\ApiInformationController@supplierinfo');
Route::get('productinfo/{id}', 'Api\ApiInformationController@productinfo');
Route::get('order/{id}', 'Api\ApiInformationController@orderinfo')->name('orderinfo.api');
Route::get('return/{id}', 'Api\ApiInformationController@returninfo')->name('returninfo.api');
Route::get('paymentmethod/{id}', 'Api\ApiInformationController@paymentmethodinfo');
Route::get('sale/{id}', 'Api\ApiInformationController@saleinfo')->name('saleinfo.api');
Route::get('purchase/{id}', 'Api\ApiInformationController@purchaseinfo')->name('purchaseinfo.api');
Route::get('social', 'Api\ApiInformationController@social')->name('social.api');
Route::get('priceinfo/{id}', 'Api\ApiInformationController@priceInfo')->name('price.api');
Route::get('pendingsales/{id}', 'Api\ApiInformationController@pendingSalesInfo')->name('pendingsaleinfo.api');
Route::get('pendingreturns/{id}', 'Api\ApiInformationController@pendingReturnInfo')->name('pendingreturninfo.api');
Route::get('invdueinfo/{id}', 'Api\ApiInformationController@invdueinfo');
Route::get('pendingdeliveryinfo/{id}', 'Api\ApiInformationController@pendingDeliveryInfo')->name('pendingdeliveryinfo.api');
Route::get('deliveryman', 'Api\ApiInformationController@deliveryman')->name('deliveryman.api');
