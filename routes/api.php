<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\{
    AuthController,
    BrandsController,
    CategoriesController,
    LocationController, 
    ProductController , 
    OrderController
};
use Illuminate\Foundation\Auth\EmailVerificationRequest;


Route::group([
    'prefix' => 'auth'
], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/register', 'register')->name('register');
        Route::post('/login', 'login')->name('login');
        Route::post('/logout', 'logout')->middleware('auth:api')->name('logout');
        Route::post('/refresh', 'refresh')->middleware('auth:api')->name('refresh');
        Route::get('/me', 'me')->middleware('auth:api')->name('me');
    });
});

Route::group([
    'prefix' => 'brands'
], function () {
    Route::controller(BrandsController::class)->group(function () {
        Route::get('/getBrands', 'index');
        Route::get('/getBrandById/{id}', 'show');
        Route::post('/createBrand', 'store')->middleware(AdminMiddleware::class);;
        Route::put('/updateBrand/{id}', 'update_brand')->middleware(AdminMiddleware::class);;
        Route::delete('/deleteBrand/{id}', 'delete_brand')->middleware(AdminMiddleware::class);;
    });
});


Route::group([
    'prefix' => 'categories'
], function () {
    Route::controller(CategoriesController::class)->group(function () {
        Route::get('/getCategories', 'index');
        Route::get('/getCategoryById/{id}', 'show');
        Route::post('/createCategory', 'store')->middleware(AdminMiddleware::class);;
        Route::put('/updateCategory/{id}', 'update_category')->middleware(AdminMiddleware::class);;
        Route::delete('/deleteCategory/{id}', 'delete_category')->middleware(AdminMiddleware::class);;
    });
});


Route::group([
    'prefix' => 'locations'
], function () {
    Route::controller(LocationController::class)->group(function () {
        Route::post('/createLocation', 'store');
        Route::put('/updateLocation/{id}', 'update_location');
        Route::delete('/deleteLocation/{id}', 'delete_location');
    });
});

Route::group([
    'prefix' => 'products'
], function () {
    Route::controller(ProductController::class)->group(function () {
        Route::get('/getProducts', 'index');
        Route::get('/getProductById/{id}', 'show');
        Route::post('/createProduct', 'store')->middleware(AdminMiddleware::class);;
        Route::put('/updateProduct/{id}', 'update_product')->middleware(AdminMiddleware::class);;
        Route::delete('/deleteProduct/{id}', 'delete_product')->middleware(AdminMiddleware::class);;
    });
});

Route::group([
    'prefix' => 'orders'
], function () {
    Route::controller(OrderController::class)->group(function () {
        Route::get('/getOrders', 'index');
        Route::get('/getOrderById/{id}', 'show');
        Route::get('/getUserOrders/{id}', 'get_user_orders');
        Route::get('/getOrderItems/{id}', 'get_order_items');
        Route::post('/createOrder', 'store')->middleware(AdminMiddleware::class);;
        Route::put('/updateOrderStatus/{id}', 'change_order_status');
        Route::delete('/deleteOrder/{id}', 'delete_order');
    });
});