<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\SystemInformationController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ExtraPageController;
use App\Http\Controllers\Front\TextController;
use App\Http\Controllers\Front\AuthController;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Admin\GeneralConfigController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\WhoWeAreController;
use App\Http\Controllers\Admin\HeroController;
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

Route::prefix('admin/hero')->middleware(['auth'])->group(function () {
    Route::get('/', [HeroController::class, 'index'])->name('hero.index');
    Route::post('/update', [HeroController::class, 'update'])->name('hero.update');
});

Route::prefix('admin/who-we-are')->middleware(['auth'])->group(function () {
    Route::get('/', [WhoWeAreController::class, 'index'])->name('whoweare.index');
    Route::post('/update', [WhoWeAreController::class, 'update'])->name('whoweare.update');
});

// Admin Portfolio Management Group
Route::prefix('admin/portfolio')->middleware(['auth'])->group(function () {
    
    // ১. Portfolio CRUD (Resource Route)
    // এটি index, create, store, show, edit, update, destroy সব রাউট তৈরি করবে
    Route::resource('projects', PortfolioController::class)->names([
        'index'   => 'portfolio.index',
        'create'  => 'portfolio.create',
        'store'   => 'portfolio.store',
        'show'    => 'portfolio.show',
        'edit'    => 'portfolio.edit',
        'update'  => 'portfolio.update',
        'destroy' => 'portfolio.destroy',
    ]);
// Inside Route::prefix('admin/portfolio')->group(function () { ... })
Route::get('/get-subcategories/{serviceId}', [PortfolioController::class, 'getSubcategories'])->name('portfolio.get_subcategories');
    // ২. Portfolio Header Settings
    Route::get('/header-settings', [PortfolioController::class, 'headerSettings'])->name('portfolio.header.settings');
    Route::post('/header-settings/update', [PortfolioController::class, 'headerUpdate'])->name('portfolio.header.update');
});
// Admin Service Management Group
Route::prefix('admin/service')->middleware(['auth'])->group(function () {
    
    // ১. Service CRUD (Resource Route)
    // এটি index, create, store, show, edit, update, destroy সব রাউট তৈরি করবে
    Route::resource('items', ServiceController::class)->names([
        'index'   => 'service.index',
        'create'  => 'service.create',
        'store'   => 'service.store',
        'show'    => 'service.show',
        'edit'    => 'service.edit',
        'update'  => 'service.update',
        'destroy' => 'service.destroy',
    ]);
// Inside Route::prefix('admin/portfolio')->group(function () { ... })

    // ২. Service Header Settings
    Route::get('/header-settings', [ServiceController::class, 'headerSettings'])->name('service.header.settings');
    Route::post('/header-settings/update', [ServiceController::class, 'headerUpdate'])->name('service.header.update');
});


// Admin Team Management Group
Route::prefix('admin/team')->middleware(['auth'])->group(function () {
    
    // ১. Team Members (CRUD using Resource Route)
    // এটি index, create, store, edit, update, destroy সব রাউট তৈরি করবে
    Route::resource('members', TeamController::class)->names([
        'index'   => 'team.index',
        'create'  => 'team.create',
        'store'   => 'team.store',
        'edit'    => 'team.edit',
        'show'    => 'team.show',
        'update'  => 'team.update',
        'destroy' => 'team.destroy',
    ]);

    // ২. Team Header Settings (ট্যাব সিস্টেমের জন্য)
    Route::get('/header-settings', [TeamController::class, 'headerSettings'])->name('team.header.settings');
    Route::post('/header-settings/update', [TeamController::class, 'headerUpdate'])->name('team.header.update');
});

// Admin Testimonial Management Group
Route::prefix('admin/testimonial')->middleware(['auth'])->group(function () {
    
    // Testimonial Items (CRUD using Resource Route)
    // এটি index, create, store, edit, update, destroy সব রাউট অটো জেনারেট করবে
    Route::resource('items', TestimonialController::class)->names([
        'index'   => 'testimonial.index',
        'create'  => 'testimonial.create',
        'store'   => 'testimonial.store',
        'edit'    => 'testimonial.edit',
        'update'  => 'testimonial.update',
        'destroy' => 'testimonial.destroy',
    ]);

    // Testimonial Header Settings (ট্যাব সিস্টেমের জন্য আলাদা রাউট)
    Route::get('/header-settings', [TestimonialController::class, 'headerSettings'])->name('testimonial.header.settings');
    Route::post('/header-settings/update', [TestimonialController::class, 'headerUpdate'])->name('testimonial.header.update');
});


Route::prefix('admin/contact')->middleware(['auth'])->group(function () {
    
    // 1. User Messages Routes
    Route::get('/messages', [ContactMessageController::class, 'index'])->name('contact.messages.index');
    Route::get('/messages/{id}', [ContactMessageController::class, 'view'])->name('contact.messages.view');
    Route::delete('/messages/{id}', [ContactMessageController::class, 'destroy'])->name('contact.messages.destroy');
    Route::post('/messages/multi-delete', [ContactMessageController::class, 'multiDelete'])->name('contact.messages.multiDelete');

    // 2. Quick Audit Routes (নতুন যুক্ত করা হয়েছে)
    Route::get('/quick-audits', [ContactMessageController::class, 'quickAuditIndex'])->name('contact.quick_audits.index');
    Route::get('/quick-audits/{id}', [ContactMessageController::class, 'quickAuditView'])->name('contact.quick_audits.view');
    Route::delete('/quick-audits/{id}', [ContactMessageController::class, 'quickAuditDestroy'])->name('contact.quick_audits.destroy');
    Route::post('/quick-audits/multi-delete', [ContactMessageController::class, 'quickAuditMultiDelete'])->name('contact.quick_audits.multiDelete');

    // 3. Contact Header Settings Routes
    Route::get('/header-settings', [ContactMessageController::class, 'headerSettings'])->name('contact.header.settings');
    Route::post('/header-settings/update', [ContactMessageController::class, 'headerUpdate'])->name('contact.header.update');
});


Route::get('/general-config', [GeneralConfigController::class, 'index'])->name('general.config');
    Route::post('/general-config/update', [GeneralConfigController::class, 'update'])->name('general.config.update');



 

    Route::controller(AuthController::class)->group(function () {

        Route::get('/user-dashboard', 'userDashboard')->name('front.userDashboard');
        Route::post('/profile/update', 'updateProfile')->name('profile.update');
        Route::post('/password/update', 'updatePassword')->name('password.update');
});
    //website part




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

    
    Route::resource('offer', OfferController::class);


    
   

    


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