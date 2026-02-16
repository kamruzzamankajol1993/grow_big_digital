<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\SystemInformationController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ClientSayController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ExtraPageController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\AboutUsController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Front\TextController;
use App\Http\Controllers\Front\AuthController;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Front\CustomerPersonalController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\SizeChartController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SidebarMenuController;
use App\Http\Controllers\Admin\OfferSectionController;
use App\Http\Controllers\Admin\SliderControlController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RewardPointController;
use App\Http\Controllers\Admin\CompanyCategoryController;
use App\Http\Controllers\Admin\HighlightProductController;
use App\Http\Controllers\Admin\ExtraCategoryController;
use App\Http\Controllers\Admin\HeroLeftSliderController;
use App\Http\Controllers\Admin\HeroRightSliderController;
use App\Http\Controllers\Admin\FooterBannerController;
use App\Http\Controllers\Admin\AreaWisePriceController;
use App\Http\Controllers\Admin\MainBrandController;
// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/clear', function() {
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    return redirect()->back();
});


//frontend part start 
Route::post('/contact-us-post', [App\Http\Controllers\Front\FrontController::class, 'contactUsPost'])->name('front.contactUsPost');
Route::controller(FrontController::class)->group(function () {

Route::get('/user/quote-details/{id}', 'getQuoteDetailsHtml')->name('front.quote.details.html');
    Route::post('/submit-quote', 'submitQuote')->name('front.submitQuote');
Route::get('/user/order-details/{id}', 'getOrderDetailsHtml')->name('front.order.details.html');
Route::get('/user/order-print/{id}', 'orderPrint')->name('front.order.print');
    Route::get('/', 'index')->name('front.index');
    Route::get('/about_us', 'aboutUs')->name('front.aboutUs');
    Route::get('/contact-us', 'contactUs')->name('front.contactUs');
    Route::post('/contact-us-post', 'contactUsPost')->name('front.contactUsPost');
    Route::get('/services', 'services')->name('front.services');

    // --- Cart / Quote Request Routes ---
    Route::post('/add-to-cart', 'addToCart')->name('front.addToCart');
    Route::get('/get-cart-content', 'getCartContent')->name('front.getCartContent');
    Route::post('/update-cart-qty', 'updateCartQty')->name('front.updateCartQty');
    Route::post('/remove-from-cart', 'removeFromCart')->name('front.removeFromCart');

    // --- নতুন ২ টি রাউট ---
    // ১. যদি ক্যাটাগরির ব্র্যান্ড/কোম্পানি থাকে
    Route::get('/category/companies/{slug}', 'categoryWiseCompanies')->name('front.category.companies');

    // ২. যদি ক্যাটাগরির ব্র্যান্ড না থাকে (সরাসরি প্রোডাক্ট)
    Route::get('/category/products/{slug}', 'categoryWiseProducts')->name('front.category.products');


    // ১. যদি কোম্পানির ক্যাটাগরি থাকে: কোম্পানির ক্যাটাগরি লিস্ট পেজ
    Route::get('/company/{slug}/categories', 'companyWiseCategories')->name('front.company.categories');

    // ২. যদি কোম্পানির ক্যাটাগরি না থাকে: সরাসরি কোম্পানির প্রোডাক্ট পেজ
    Route::get('/company/{slug}/products', 'companyWiseProducts')->name('front.company.products');

    Route::get('/product-details/{slug}', 'productDetails')->name('front.product.details');


    // ২. যদি সাব-ক্যাটাগরি থাকে: সেই সাব-ক্যাটাগরিগুলো দেখানোর রাউট
    Route::get('/company/category/{slug}/sub-categories', 'companyCategorySubCategories')->name('front.company.category.subcategories');

    // ৩. যদি সাব-ক্যাটাগরি না থাকে: সরাসরি প্রোডাক্ট দেখানোর রাউট
    Route::get('/company/category/{slug}/products', 'companyCategoryProducts')->name('front.company.category.products');

   }); 


//frontend part end

// Highlight Product Routes
    Route::get('highlight-product', [HighlightProductController::class, 'index'])->name('highlight-product.index');
    Route::post('highlight-product', [HighlightProductController::class, 'store'])->name('highlight-product.store');

    // Homepage Section Routes (Single Page)
    Route::get('homepage-section', [App\Http\Controllers\Admin\HomepageSectionController::class, 'index'])->name('homepage-section.index');
    Route::post('homepage-section', [App\Http\Controllers\Admin\HomepageSectionController::class, 'update'])->name('homepage-section.update');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/payment/success', [FrontController::class, 'paymentSuccess'])->name('payment.success');
Route::post('/payment/fail', [FrontController::class, 'paymentFail'])->name('payment.fail');
Route::post('/payment/cancel', [FrontController::class, 'paymentCancel'])->name('payment.cancel');

Route::resource('customerPersonalTicket', CustomerPersonalController::class);

Route::controller(CustomerPersonalController::class)->group(function () {
    Route::get('/customerGeneralTicketPdf/{id}', 'customerGeneralTicketPdf')->name('customerGeneralTicketPdf');
Route::get('/customerPersonalTicketPdf/{id}', 'customerPersonalTicketPdf')->name('customerPersonalTicketPdf');
    Route::get('/customerPersonalTicket', 'customerPersonalTicket')->name('customerPersonalTicket');
});
Route::controller(LoginController::class)->group(function () {

    Route::get('/admin_login_page', 'viewLoginPage')->name('viewLoginPage');
    Route::get('/password/reset', 'showLinkRequestForm')->name('showLinkRequestForm');
    Route::post('/password/reset/submit', 'reset')->name('reset');

});

Route::controller(TextController::class)->group(function () {
    Route::post('/textMessageAll', 'textMessage')->name('text.index');
});

Route::controller(AuthController::class)->group(function () {
// Forgot Password Direct Steps
    Route::post('/check-email-for-reset', 'checkEmailForReset')->name('front.checkEmailForReset');
    Route::post('/direct-password-reset', 'directPasswordReset')->name('front.directPasswordReset');

    Route::get('/login-register', 'loginregisterPage')->name('front.loginRegister');

    Route::post('/login-user-post', 'loginUserPost')->name('front.loginUserPost');
    Route::post('/register-user-post', 'registerUserPost')->name('front.registerUserPost');

      // --- NEW PASSWORD RESET ROUTES ---
    Route::get('forgot-password', 'showForgotPasswordForm')->name('front.password.request');
    Route::post('forgot-password', 'sendResetLink')->name('front.password.email');
    Route::get('reset-password/{token}', 'showResetPasswordForm')->name('front.password.reset');
    Route::post('reset-password', 'resetPassword')->name('front.password.update'); // Note: This reuses the standard 'password.update' name
});


    



Route::group(['middleware' => ['auth']], function() {



    Route::controller(App\Http\Controllers\Admin\ProductController::class)->group(function () {
    // আগের রাউটগুলো...
    
    // NEW AJAX ROUTES
    Route::get('/get-brands-by-category/{categoryId}', 'getBrandsByCategory')->name('get.brands.by.category');
    Route::get('/get-company-categories-by-brand/{brandId}', 'getCompanyCategoriesByBrand')->name('get.company.categories.by.brand');
});

    // Company Category Routes
    Route::get('company-category/get-by-company/{id}', [CompanyCategoryController::class, 'getCategoriesByCompany'])->name('company-category.get-by-company');

    Route::get('company-category/import/sample', [CompanyCategoryController::class, 'downloadSample'])->name('company-category.import.sample');
    Route::post('company-category-import', [CompanyCategoryController::class, 'import'])->name('company-category.import');

    Route::get('ajax_company_categories', [CompanyCategoryController::class, 'data'])->name('ajax.company-category.data');
    Route::resource('company-category', CompanyCategoryController::class);

    Route::get('main_brands/import/sample', [MainBrandController::class, 'downloadSample'])->name('main_brand.import.sample');
Route::post('main-brands-import', [MainBrandController::class, 'import'])->name('main_brand.import');

// Place this near your existing Brand/Company routes
Route::get('brands/import/sample', [BrandController::class, 'downloadSample'])->name('brand.import.sample');
Route::post('brands-import', [BrandController::class, 'import'])->name('brand.import');
    // Place this near your existing Category routes
Route::get('categories/import/sample', [CategoryController::class, 'downloadSample'])->name('category.import.sample');
Route::post('categories-import', [CategoryController::class, 'import'])->name('category.import');

Route::get('products/import/sample', [App\Http\Controllers\Admin\ProductController::class, 'downloadSample'])->name('product.import.sample');
    // In routes/web.php, inside the auth middleware group
Route::post('products-import', [App\Http\Controllers\Admin\ProductController::class, 'import'])->name('product.import');


   

    // Shareholder List Routes
    Route::get('/shareholders', [UserController::class, 'shareholderIndex'])->name('shareholders.index');
    Route::get('/ajax-shareholders-data', [UserController::class, 'shareholdersData'])->name('ajax.shareholders.data');

    

    Route::prefix('reward-points')->name('reward.')->group(function () {
        Route::get('data', [RewardPointController::class, 'data'])->name('data');
    Route::get('settings', [RewardPointController::class, 'settings'])->name('settings');
    Route::post('settings', [RewardPointController::class, 'updateSettings'])->name('settings.update');
    Route::get('history', [RewardPointController::class, 'history'])->name('history');
    Route::get('history/{customer}', [RewardPointController::class, 'customerHistory'])->name('customer.history');
});

Route::post('/order-customer-quick-store', [OrderController::class, 'quickStoreCustomer'])->name('order.customer.quick-store');
    // Add this to your admin route group
Route::post('orders/bulk-update-status', [OrderController::class, 'bulkUpdateStatus'])->name('order.bulk-update-status');
    Route::post('order-payment/{order}', [OrderController::class, 'storePayment'])->name('order.payment.store');
Route::get('order-print-a4/{order}', [OrderController::class, 'printA4'])->name('order.print.a4');
Route::get('order-print-pos/{order}', [OrderController::class, 'printPOS'])->name('order.print.pos');
Route::get('order-print-a5/{order}', [OrderController::class, 'printA5'])->name('order.print.a5');
Route::post('/order/update-status-prices/{id}', [OrderController::class, 'updateStatusWithPrices'])->name('order.update.status.prices');
Route::get('order-search-customers', [OrderController::class, 'searchCustomers'])->name('order.search-customers');

       Route::get('ajax_orders', [OrderController::class, 'data'])->name('ajax.order.data');
        Route::post('storeorder-update-status/{order}', [OrderController::class, 'updateStatus'])->name('order.update-status');
    Route::get('orderstore_details/{id}', [OrderController::class, 'getDetails'])->name('order.get-details');
    Route::get('ordersdestroymultiple', [OrderController::class, 'destroyMultiple'])->name('order.destroy-multiple');
    Route::resource('order', OrderController::class);

     Route::get('order-get-customer-details/{id}', [OrderController::class, 'getCustomerDetails'])->name('order.get-customer-details');
    Route::get('order-search-products', [OrderController::class, 'searchProducts'])->name('order.search-products');
 Route::get('order-get-product-details/{id}', [OrderController::class, 'getProductDetails'])->name('order.get-product-details'); // Add this
    Route::get('slider-control', [SliderControlController::class, 'index'])->name('slider.control.index');
    Route::post('slider-control', [SliderControlController::class, 'update'])->name('slider.control.update');
    Route::get('slider-control/search', [SliderControlController::class, 'searchProducts'])->name('slider.control.search');

    
   

    
Route::get('ajax_brands', [BrandController::class, 'data'])->name('ajax.brand.data');
Route::resource('brand', BrandController::class);

Route::get('ajax_category', [CategoryController::class, 'data'])->name('ajax.category.data');
Route::resource('category', CategoryController::class);

Route::get('ajax_subcategory', [SubCategoryController::class, 'data'])->name('ajax.subcategory.data');
Route::resource('subcategory', SubCategoryController::class);



// Sub-Subcategory Routes
    Route::get('get-subcategories/{categoryId}', [SubSubcategoryController::class, 'getSubcategories'])->name('get.subcategories');
    Route::get('ajax_ub-subcategories', [SubSubcategoryController::class, 'data'])->name('ajax.sub-subcategory.data');
    Route::resource('sub-subcategory', SubSubcategoryController::class);


  

Route::get('products/export-variants-stock', [App\Http\Controllers\Admin\ProductController::class, 'exportVariantsStock'])->name('product.export.variants');
// Product Routes
Route::post('products/bulk-status-update', [App\Http\Controllers\Admin\ProductController::class, 'bulkStatusUpdate'])->name('ajax.product.bulk-status-update');
    Route::get('ajax_products', [ProductController::class, 'data'])->name('ajax.product.data');
        Route::get('get_subcategories/{categoryId}', [ProductController::class, 'getSubcategories'])->name('get_subcategories');
    Route::get('get-sub-subcategories/{subcategoryId}', [ProductController::class, 'getSubSubcategories'])->name('get.sub-subcategories');
    Route::get('get-size-chart-entries/{id}', [ProductController::class, 'getSizeChartEntries'])->name('get.size-chart.entries');
    Route::resource('product', ProductController::class);
Route::get('ajax_products_delete', [ProductController::class, 'ajax_products_delete'])->name('ajax_products_delete');




    Route::controller(AuthController::class)->group(function () {

        Route::get('/user-dashboard', 'userDashboard')->name('front.userDashboard');
        Route::post('/profile/update', 'updateProfile')->name('profile.update');
        Route::post('/password/update', 'updatePassword')->name('password.update');
});
    //website part




Route::resource('defaultLocation', DefaultLocationController::class);
    Route::resource('searchLog', SearchLogController::class);

     Route::controller(SearchLogController::class)->group(function () {

    Route::get('/ajax-table-searchLog/data','data')->name('ajax.searchLogtable.data');


    });

    Route::resource('aboutUs', AboutUsController::class);
    Route::resource('contact', ContactController::class);

    Route::resource('banner', BannerController::class);
    Route::resource('clientSay', ClientSayController::class);
    //Route::resource('review', ReviewController::class);
    // Review Routes
    Route::resource('review', ReviewController::class);
    Route::get('ajax/reviews/data', [ReviewController::class, 'data'])->name('ajax.review.data');
    Route::resource('newsAndMedia', NewsAndMediaController::class);
    Route::resource('gallery', GalleryController::class);
    Route::resource('socialLink', SocialLinkController::class);
    Route::resource('blog', BlogController::class);
    Route::resource('extraPage', ExtraPageController::class);
    Route::resource('message', MessageController::class);

    //setting part start
    Route::resource('setting', SettingController::class);
    Route::resource('branch', BranchController::class);
    Route::resource('main_brand', MainBrandController::class);
    Route::resource('designation', DesignationController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('systemInformation', SystemInformationController::class);


    Route::get('ajax-customers', [CustomerController::class, 'data'])->name('ajax.customer.data');
    Route::resource('customer', CustomerController::class);

    Route::resource('service', ServiceController::class);
    Route::resource('offer', OfferController::class);


    Route::controller(ServiceController::class)->group(function () {
    
        Route::get('/service/export','exportServices')->name('service.export');
    });

   

    


    Route::controller(CustomerController::class)->group(function () {
Route::get('/customers/export','exportCustomers')->name('customer.export');
        Route::get('/customers/check-email','checkEmailUniqueness')->name('customers.checkEmail');

    Route::get('/downloadcustomerPdf','downloadcustomerPdf')->name('downloadcustomerPdf');
    Route::get('/downloadcustomerExcel','downloadcustomerExcel')->name('downloadcustomerExcel');
    Route::get('/ajax-table-customer/data','data')->name('ajax.customertable.data');


    });





    Route::controller(UserController::class)->group(function () {

    Route::get('/downloadUserPdf','downloadUserPdf')->name('downloadUserPdf');
    Route::get('/downloadUserExcel','downloadUserExcel')->name('downloadUserExcel');
    Route::get('/ajax-table-user/data','data')->name('ajax.usertable.data');


    });

    




    Route::controller(SystemInformationController::class)->group(function () {

    Route::get('/downloadSystemInformationPdf','downloadSystemInformationPdf')->name('downloadSystemInformationPdf');
    Route::get('/downloadSystemInformationExcel','downloadSystemInformationExcel')->name('downloadSystemInformationExcel');
    Route::get('/ajax-table-systemInformation/data','data')->name('ajax.systemInformationtable.data');


    });



    Route::controller(RoleController::class)->group(function () {

    Route::get('/downloadRolePdf','downloadRolePdf')->name('downloadRolePdf');
    Route::get('/downloadRoleExcel','downloadRoleExcel')->name('downloadRoleExcel');
    Route::get('/ajax-table-role/data','data')->name('ajax.roletable.data');


    });


     Route::controller(PermissionController::class)->group(function () {

    Route::get('/downloadPermissionPdf','downloadPermissionPdf')->name('downloadPermissionPdf');
    Route::get('/downloadPermissionExcel','downloadPermissionExcel')->name('downloadPermissionExcel');
    Route::get('/ajax-table-permission/data','data')->name('ajax.permissiontable.data');


    });


   

    Route::controller(UserController::class)->group(function () {


        Route::get('/activeOrInActiveUser/{status}/{id}', 'activeOrInActiveUser')->name('activeOrInActiveUser');

    });


    Route::controller(SettingController::class)->group(function () {

        Route::get('/error_500', 'error_500')->name('error_500');
        Route::get('/profileView', 'profileView')->name('profileView');
        Route::get('/profileSetting', 'profileSetting')->name('profileSetting');

        Route::post('/profileSettingUpdate', 'profileSettingUpdate')->name('profileSettingUpdate');
        Route::post('/passwordUpdate', 'passwordUpdate')->name('passwordUpdate');

        Route::post('/checkMailPost', 'checkMailPost')->name('checkMailPost');
        Route::get('/checkMailForPassword', 'checkMailForPassword')->name('checkMailForPassword');

        Route::get('/newEmailNotify', 'newEmailNotify')->name('newEmailNotify');
        Route::post('/postPasswordChange', 'postPasswordChange')->name('postPasswordChange');
        Route::get('/accountPasswordChange/{id}', 'accountPasswordChange')->name('accountPasswordChange');




    });
    //setting part end
});