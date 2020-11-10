<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/','Frontend\HomepageController@index')->name('homepage.index');
Route::get('contact','Frontend\ContactpageController@index')->name('contactpage.index');
Route::post('contact','Frontend\ContactpageController@contactus')->name('contactpage.submit');

Route::get('shop','Frontend\ShoppageController@index')->name('shoppage.index');
Route::get('pages/{slug}','Frontend\PageController@index')->name('frontendpage.index');





Route::get('collection/{id}','Frontend\ShoppageController@categoryproduct')->name('collection.view');
Route::get('shop/category/{id}','Frontend\ShoppageController@categoryproduct')->name('shoppage.category');
Route::get('type/{id}','Frontend\ShoppageController@subcategoryproduct')->name('shoppage.subcategory');

Route::get('brand/{id}','Frontend\ShoppageController@brandproduct')->name('shoppage.brand');

Route::get('shop/tag/{id}','Frontend\ShoppageController@tagproduct')->name('shoppage.tagproduct');
Route::post('shop/filterbyprice','Frontend\ShoppageController@filterbyprice')->name('shoppage.filterbyprice');
Route::get('shop/filterbyprice','Frontend\ShoppageController@redirectmain')->name('shoppage.redirectmain');

Route::get('/search','Frontend\SearchController@index')->name('search.index');
Route::get('myaccount/profile','Frontend\ProfileController@show')->name('profile.show');
Route::get('myaccount/profile/edit','Frontend\ProfileController@editprofile')->name('profile.editprofile');
Route::put('myaccount/profile/update','Frontend\ProfileController@update')->name('profile.update');
Route::post('productcomments/{id}','CommentsController@store')->name('comments.store');

Route::get('myaccount/profile/changepassword','Frontend\ProfileController@changepassword')->name('profile.changepassword');

Route::put('myaccount/profile/passupdate','Frontend\ProfileController@passupdate')->name('profile.passupdate');

Route::get('myaccount/orders','Frontend\OrderController@show')->name('orders.show');
Route::post('myaccount/ordercancel/{id}','Frontend\OrderController@orderdestroy')->name('order.orderdestory');
Route::get('product/{id}','Frontend\SingleProductController@show')->name('singleproduct.index');

Route::get('/deal/product/{id}','Frontend\SingleProductController@deal')->name('singleproduct.deal');
Route::get('shop/cart','Frontend\CartpageController@index')->name('cartpage.index');
Route::get('shop/cart/checkout','Frontend\CheckoutPageController@index')->name('checkoutpage.index');
Route::post('shop/cart/checkout','Frontend\CheckoutPageController@store')->name('checkoutpage.store');
Route::post('shop/cart/checkout/{id}','Frontend\CheckoutPageController@oldcustomerorder')->name('checkoutpage.oldcustomerorder');

Route::get('shop/cart/checkout/confirmation/{id}','Frontend\OrderController@confirmation')->name('order.confirmation');
Route::get('shop/cart/checkout/invoice/{id}','Frontend\OrderInvoiceController@show')->name('orderinvoice.show');
Route::get('shop/cart/charges','Frontend\CartpageController@charge')->name('cartpage.charge');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('admin/login', 'Auth\AdminLoginController@adminLogin')->name('admin.login');
Route::post('admin/login', 'Auth\AdminLoginController@adminLoginSubmit')->name('admin.loginsubmit');






//Access For All Admin

Route::group(['prefix'=> 'admin','middleware' => ['auth:admin']], function(){

    Route::resource('admininfo','adminController');
    Route::resource('rp/role','RoleController');
    Route::resource('rp/permissions','RoleController');

    Route::get('dashboard', 'adminController@dashboard')->name('admin.dashboard');
    Route::get('inventory/dashboard', 'adminController@inventorydashboard')->name('admin.inventorydashboard');
    Route::get('logout', 'Auth\AdminLoginController@adminLogout')->name('admin.logout');
    Route::get('action/changepassword','adminController@changepassword')->name('admin.changepassword');
    Route::put('action/changepassword/update','adminController@passUpdate')->name('admin.passupdate');
    Route::get('myaccount/profile','adminController@profile')->name('admin.profile');
    Route::get('myaccount/profile/edit','adminController@editprofile')->name('admin.editprofile');
    Route::put('myaccount/profile/update','adminController@updateprofile')->name('admin.updateprofile');

    Route::get('stock/saleshistory/{id}', 'StockController@saleshistory')->name('stock.saleshistory');
    Route::get('stock/purchasehistory/{id}', 'StockController@purchasehistory')->name('stock.purchasehistory');
    Route::get('stock/returnhistory/{id}', 'StockController@returnhistory')->name('stock.returnhistory');
    Route::get('stock/orderhistory/{id}', 'StockController@orderhistory')->name('stock.orderhistory');
    Route::get('stock/damagehistory/{id}', 'StockController@damagehistory')->name('stock.damagehistory');
    Route::get('stock/freehistory/{id}', 'StockController@freehistory')->name('stock.freehistory');
    Route::get('stock', 'StockController@index')->name('stock.index');
    Route::post('stock/export', 'StockController@export')->name('stock.export');
    Route::resource('damages','DamageController');
    Route::resource('expense','ExpenseController');
    Route::get('expensecatlist','ExpenseController@catlist')->name('expensecatlist');
    Route::resource('expensecategories','ExpenseCategoryController');

    Route::get('last10expense','ExpenseController@last10')->name('expense.last10');
    Route::post('expense/datewise','ExpenseController@datewise')->name('expense.datewise');
    Route::get('expense/datewise/{start}/{end}','ExpenseController@datewiseGetMethod')->name('expense.datewisegetmethod');

    Route::get('inventory/dashboard/viewsales/{id}', 'Pos\SaleController@show')->name('viewsales.show');
    Route::post('pos/sale/delivery/{id}', 'Pos\SaleController@delivery')->name('sale.delivery');


    //Report Related Route
    Route::get('report/marketingreport', 'ReportController@MarketingReport')->name('report.marketingreport');
    Route::post('report/marketingreport', 'ReportController@ShowMarketingReport')->name('report.showmarketingreport');



    Route::get('report/pos/salesreport', 'ReportController@SalesReport')->name('report.possalesreport');
    Route::post('report/pos/salesreport', 'ReportController@SalesReportResult')->name('report.possalesresult');
    Route::post('report/pos/salesreport/pdf', 'ReportController@pdfSalesReport')->name('report.pdfpossalesresult');



    Route::get('report/pos/deliveryreport', 'ReportController@DeliveryReport')->name('report.posdeliveryreport');
    Route::post('report/pos/deliveryreport', 'ReportController@ShowDeliveryReport')->name('report.posdeliveryresult');
    Route::post('report/pos/deliveryreport/pdf', 'ReportController@pdfDeliveryReport')->name('report.pdfposdeliveryresult');

    Route::get('report/supplierdue', 'ReportController@supplierdue')->name('report.supplierdue');
    Route::post('report/supplierdue', 'ReportController@showsupplierdue')->name('report.showsupplierdue');
    Route::post('report/supplierdue/pdf', 'ReportController@pdfsupplierdue')->name('report.pdfsupplierdue');


    Route::get('report/pos/duereport/', 'ReportController@InvDueReport')->name('report.duereport');
    Route::post('report/pos/duereport/result', 'ReportController@InvDueReportResult')->name('report.duereportresult');
    Route::post('report/pos/duereport/pdf', 'ReportController@pdfInvDueReportResult')->name('report.pdfduereportresult');

    

    Route::get('report/stockreport', 'StockController@stockreport')->name('stockreport.report');
    Route::post('report/stockreport', 'StockController@stockreportshow')->name('stockreport.show');
    Route::post('report/stockreport/pdf', 'StockController@stockreportpdf')->name('stockreport.pdf');
    Route::get('report/ecom/ecomuserstatement', 'ReportController@ecomUserStatement')->name('report.ecomuserstatement');
    Route::post('report/ecom/ecomuserstatement', 'ReportController@showEcomUserstatement')->name('report.showecomuserstatement');
    Route::post('report/ecom/ecomuserstatement/pdf', 'ReportController@pdfEcomUserstatement')->name('report.pdfcomuserstatement');
    Route::get('report/ecom/divisiowisenreport/', 'ReportController@EcomDivisionReport')->name('report.ecomdivisionreport');
    Route::post('report/ecom/divisiowisenreport/result', 'ReportController@EcomDivisionReportResult')->name('report.ecomdivisionreportresult');
    Route::post('report/ecom/divisiowisenreport/result/pdf', 'ReportController@pdfEcomDivisionReportResult')->name('report.pdfecomdivisionreportresult');
    //Inventory Customer Report
    Route::get('report/pos/posuserstatement', 'ReportController@posUserStatement')->name('report.posuserstatement');
    Route::post('report/pos/posuserstatement', 'ReportController@showPosUserstatement')->name('report.showposuserstatement');
    Route::post('report/pos/posuserstatement/pdf', 'ReportController@pdfPosUserstatement')->name('report.pdfposuserstatement');
    //Inventory Customer Detail Report
    Route::get('report/pos/posdeatailstatement', 'ReportController@posDeatilStatement')->name('report.posdetailstatement');
    Route::post('report/pos/posdeatailstatement', 'ReportController@showPosDeatilStatement')->name('report.showposdetailstatement');
    Route::post('report/pos/posdeatailstatement/pdf', 'ReportController@pdfPosDeatilStatement')->name('report.pdfposdetailstatement');
    //Cash Report
    Route::get('report/cashreport', 'ReportController@cashreport')->name('report.poscashreport');
    Route::post('report/cashreport', 'ReportController@showcashreport')->name('report.showcashreport');
    Route::post('report/cashreport/pdf', 'ReportController@pdfcashreport')->name('report.pdfcashreport');





    
    //Sales Invoice
    Route::resource('pos/sale', 'Pos\SaleController');
    Route::post('pos/saleresult','Pos\SaleController@result')->name('sale.result');
    Route::post('pos/sale/{id}/invoice', 'Pos\SaleController@invoice')->name('sale.invoice');

    Route::resource('suppliersection/supplierdue', 'SupplierdueController');
    Route::resource('pos/customers', 'Pos\UserController');
    Route::post('pos/customers/export', 'Pos\UserController@export')->name('user.export');
    Route::resource('ecom/ecomcustomer', 'Ecom\UserController');
    Route::resource('product_section/products', 'ProductController');
    Route::post('product_section/products/export', 'ProductController@export')->name('product.export');
    Route::post('removegalleryimage/{id}', 'ProductController@removegalleryimage')->name('products.removegalleryimage');
    Route::resource('product_section/sizes', 'SizeController');
    Route::resource('product_section/categories', 'CategoryController');
    Route::resource('product_section/tags', 'TagsController');
    Route::resource('product_section/brands', 'BrandController');
    Route::resource('product_section/subcategories', 'SubcategoryController');
    Route::resource('ecom/order', 'OrderController');
    Route::get('ecom/order/{id}/view', 'OrderController@show')->name('order.view');
    Route::get('pos/cashresult','Pos\CashController@index');
    Route::post('pos/cashresult','Pos\CashController@result')->name('poscash.result');
    Route::resource('pos/prevdue', 'Pos\PrevdueController');
    Route::post('transfertoecom/{id}','ProductController@transfertoecom')->name('product.transfertoecom');
    Route::post('transfertoinventory/{id}','ProductController@transfertoinventory')->name('product.transfertoinventory');
    Route::resource('purchase', 'PurchaseController');
    Route::get('inventory/dashboard/cashdetails/{id}', 'adminController@inv_pendingcash')->name('invdashboard.cashdetails');



    Route::get('allprice','PriceController@index')->name('price.index');
    Route::put('allprice/{id}','PriceController@update')->name('price.update');
    Route::get('tp','PriceController@tpindex')->name('tp.index');
    Route::put('tp/{id}','PriceController@tpupdate')->name('tp.update');
    Route::resource('suppliersection/payment', 'PaymentController');
    Route::get('ecom/charge', 'ChargeController@index')->name('charge.index');
    Route::get('ecom/charge/{id}/edit', 'ChargeController@edit')->name('charge.edit');
    Route::put('ecom/charge/{id}', 'ChargeController@update')->name('charge.update');
    Route::post('order/cash/{id}', 'OrderController@cashSubmit')->name('order.cashsubmit');
    Route::put('order/approval/{id}', 'OrderController@approval')->name('order.approval');
    Route::put('order/shipping/{id}', 'OrderController@shipped')->name('order.shipped');
    Route::put('order/cance/{id}', 'OrderController@OrderCancel')->name('order.cancel');
    Route::get('order/invoice/{id}', 'OrderController@invoice')->name('order.invoice');
    Route::resource('suppliersection/suppliers', 'SupplierController');
    Route::post('purchase/result','PurchaseController@result')->name('purchase.result');
    Route::get('purchase/result','PurchaseController@index');
    Route::resource('ecom/return', 'Ecom\ReturnproductController');
    Route::resource('pos/returnproduct', 'Pos\ReturnproductController');
    Route::post('pos/returnproductresult','Pos\ReturnproductController@result')->name('returnproduct.result');
    Route::get('pos/returnproductresult','Pos\ReturnproductController@index');
    Route::post('pos/returnproduct/{id}/invoice', 'Pos\ReturnproductController@invoice')->name('returnproduct.invoice');
    Route::get('inventory/dashboard/viewreturns/{id}', 'Pos\ReturnproductController@show')->name('viewreturns.show');
    Route::resource('pos/cash', 'Pos\CashController');
    Route::resource('marketingreport','MarketingReportController');
    Route::post('marketingreport/datewiseview','MarketingReportController@datewiseview')->name('marketingreport.datewiseview');



    Route::get('company','CompanyController@index')->name('company.index');
    Route::get('company/{id}/edit','CompanyController@edit')->name('company.edit');
    Route::put('company/{id}','CompanyController@update')->name('company.update');
    Route::resource('ecom/paymentmethod', 'PaymentmethodController');
    Route::resource('ecom/sliders', 'SliderController');
    Route::resource('ecom/deliveryinfo', 'DeliveryinfoController')->only(['index' ,'edit', 'update']);
    Route::post('pos/cash/approve/{id}', 'Pos\CashController@approve')->name('cash.approve');
    Route::post('pos/cash/cancel/{id}', 'Pos\CashController@cancel')->name('cash.cancel');
    Route::post('pos/sale/approve/{id}', 'Pos\SaleController@approve')->name('sale.approve');
    Route::resource('ecom/pages', 'PageController');
    Route::post('pos/returnproduct/approve/{id}', 'Pos\ReturnproductController@approve')->name('returnproduct.approve');
    Route::resource('ecom/deals', 'Ecom\DealController');
    Route::get('ecom/menus', 'MenuController@index')->name('admin.menus');
    Route::get('ecom/advertisement','AdvertisementController@index')->name('advertisement.index');
    Route::get('ecom/advertisement/{id}/edit','AdvertisementController@edit')->name('advertisement.edit');
    Route::put('ecom/advertisement/{id}','AdvertisementController@update')->name('advertisement.update');
    Route::get('comments','CommentsController@index')->name('comments.index');
    Route::post('approvecomment/{id}','CommentsController@approve')->name('comments.approve');
    Route::post('destroycomment/{id}','CommentsController@destroy')->name('comments.destroy');
    Route::get('generaloption','GeneralOptionController@index')->name('generaloption.index');
    Route::get('generaloption/{id}/edit','GeneralOptionController@edit')->name('generaloption.edit');
    Route::put('generaloption/{id}','GeneralOptionController@update')->name('generaloption.update');
    Route::resource('emp_type','EmployeeTypeController');
    Route::resource('employee','EmployeeController');
    
});




