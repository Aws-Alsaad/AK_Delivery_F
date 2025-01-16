<?php

use App\Http\Controllers\admin_controller\AuthController2;
use App\Http\Controllers\admin_controller\ClientController;
use App\Http\Controllers\admin_controller\CsController3;
use App\Http\Controllers\admin_controller\OrderController4;
use App\Http\Controllers\admin_controller\productController;
use App\Http\Controllers\admin_controller\ProfileController2;
use App\Http\Controllers\admin_controller\SuperClientController;
use App\Http\Controllers\client_controller\AuthController;
use App\Http\Controllers\client_controller\CsController;
use App\Http\Controllers\client_controller\FavoriteController;
use App\Http\Controllers\client_controller\OrderController;
use App\Http\Controllers\client_controller\OrderProductsController;
use App\Http\Controllers\client_controller\ProfileController;
use App\Http\Controllers\client_controller\StoreController;
use App\Http\Controllers\admin_controller\StoreController2;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\super_client_controller\AuthController3;
use App\Http\Controllers\super_client_controller\CsController2;
use App\Http\Controllers\super_client_controller\OrderController3;
use App\Http\Controllers\super_client_controller\ProductController3;
use App\Http\Controllers\super_client_controller\ProfileController3;
use App\Http\Controllers\super_client_controller\StoreController3;
use Illuminate\Http\Request;
//use App\Http\Controllers\admin_controller\AuthController;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::group(['prefix' => 'client'], function () {
    Route::post('register',[AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);
    Route::post('forgotPassword1',[AuthController::class,'forgotPassword1']);
    Route::post('forgotPassword2',[AuthController::class,'forgotPassword2']);
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('passwordChange',[AuthController::class,'passwordChange']);

        Route::get('getProfile', function (Request $request) {
            return $request->user();
        });
        Route::post('passwordChanging',[ProfileController::class,'passwordChanging']);
        Route::post('addProfilePhoto',[ProfileController::class,'addProfilePhoto']);
        Route::post('addAddress',[ProfileController::class,'addAddress']);
        Route::post('changeAddress',[ProfileController::class,'changeAddress']);
        Route::post('nameChanging',[ProfileController::class,'nameChanging']);
        Route::post('deleteAccount',[ProfileController::class,'deleteAccount']);

        Route::get('getStores',[StoreController::class,'getStores']);
        Route::get('getStore',[StoreController::class,'getStore']);
        Route::get('storeSearch',[StoreController::class,'storeSearch']);
        Route::get('getProducts',[StoreController::class,'getProducts']);
        Route::get('getProduct',[StoreController::class,'getProduct']);
        Route::get('productSearch1',[StoreController::class,'productSearch1']);
        Route::get('productSearch2',[StoreController::class,'productSearch2']);

        Route::post('addOrder',[OrderController::class,'addOrder']);
        Route::post('phoneNumberOrder',[OrderController::class,'phoneNumberOrder']);
        Route::post('changeAddressOrder',[OrderController::class,'changeAddressOrder']);
        Route::post('changePhoneNumberOrder',[OrderController::class,'changePhoneNumberOrder']);
        Route::post('deleteOrder',[OrderController::class,'deleteOrder']);
        Route::get('getOrder1',[OrderController::class,'getOrder1']);
        Route::get('getOrder2',[OrderController::class,'getOrder2']);
        Route::get('getOrders',[OrderController::class,'getOrders']);
        Route::post('sendOrder',[OrderController::class,'sendOrder']);

        Route::post('addProductsOrder',[OrderProductsController::class,'addProductsOrder']);
        Route::post('changeQuantity',[OrderProductsController::class,'changeQuantity']);
        Route::post('deleteOrderProduct',[OrderProductsController::class,'deleteOrderProduct']);
        Route::get('getOrderProducts1',[OrderProductsController::class,'getOrderProducts1']);
        Route::get('getOrderProducts2',[OrderProductsController::class,'getOrderProducts2']);

        Route::post('addFavorite',[FavoriteController::class,'addFavorite']);
        Route::post('deleteFavorite',[FavoriteController::class,'deleteFavorite']);
        Route::get('getFavorite',[FavoriteController::class,'getFavorite']);

        Route::post('addCS',[CsController::class,'addCS']);

        Route::get('getClientNotification',[NotificationController::class,'getClientNotification']);
        Route::get('getNotReadNotification1',[NotificationController::class,'getNotReadNotification1']);

        Route::post('logout',[AuthController::class,'logout']);
    });
});

Route::group(['prefix' => 'superClient'], function () {
    Route::post('login',[AuthController3::class,'login']);
    Route::post('forgotPassword1',[AuthController3::class,'forgotPassword1']);
    Route::post('forgotPassword2',[AuthController3::class,'forgotPassword2']);
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('passwordChange',[AuthController3::class,'passwordChange']);

        Route::get('getProfile', function (Request $request) {
            return $request->user();
        });
        Route::post('passwordChanging',[ProfileController3::class,'passwordChanging']);
        Route::post('addProfilePhoto',[ProfileController3::class,'addProfilePhoto']);
        Route::post('nameChanging',[ProfileController3::class,'nameChanging']);
        Route::post('deleteAccount',[ProfileController3::class,'deleteAccount']);

        Route::post('changeAddressStore',[StoreController3::class,'changeAddressStore']);
        Route::post('addStorePhoneNumber',[StoreController3::class,'addStorePhoneNumber']);
        Route::post('changeStorePhoneNumber',[StoreController3::class,'changeStorePhoneNumber']);
        Route::post('deleteStorePhoneNumber',[StoreController3::class,'deleteStorePhoneNumber']);
        Route::post('addStorelink',[StoreController3::class,'addStorelink']);
        Route::post('changeStorelink',[StoreController3::class,'changeStorelink']);
        Route::post('deleteStorelink',[StoreController3::class,'deleteStorelink']);
        Route::post('changeStoreName',[StoreController3::class,'changeStoreName']);
        Route::post('addStoreProfilePhoto',[StoreController3::class,'addStoreProfilePhoto']);
        Route::get('getStore',[StoreController3::class,'getStore']);

        Route::post('addProduct',[ProductController3::class,'addProduct']);
        Route::post('deleteProduct',[ProductController3::class,'deleteProduct']);
        Route::get('getProducts',[ProductController3::class,'getProducts']);
        Route::get('getProduct',[ProductController3::class,'getProduct']);
        Route::get('productSearch',[ProductController3::class,'productSearch']);
        Route::post('addProductPhoto',[ProductController3::class,'addProductPhoto']);
        Route::post('changeProductName',[ProductController3::class,'changeProductName']);
        Route::post('changeProductPrice',[ProductController3::class,'changeProductPrice']);
        Route::post('changeProductQuantity',[ProductController3::class,'changeProductQuantity']);
        Route::post('changeProductDate',[ProductController3::class,'changeProductDate']);

        Route::post('changeOrderStatus',[OrderController3::class,'changeOrderStatus']);

        Route::post('addCS',[CsController2::class,'addCS']);

        Route::get('getSuperClientNotification',[NotificationController::class,'getSuperClientNotification']);
        Route::get('getNotReadNotification2',[NotificationController::class,'getNotReadNotification2']);

        Route::post('logout',[AuthController3::class,'logout']);
    });
});

Route::group(['prefix' => 'admin'], function () {
    Route::post('register',[AuthController2::class,'register']);
    Route::post('login',[AuthController2::class,'login']);
    Route::post('forgotPassword1',[AuthController2::class,'forgotPassword1']);
    Route::post('forgotPassword2',[AuthController2::class,'forgotPassword2']);
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('passwordChange',[AuthController2::class,'passwordChange']);

        Route::get('getProfile', function (Request $request) {
            return $request->user();
        });
        Route::post('passwordChanging',[ProfileController2::class,'passwordChanging']);
        Route::post('nameChanging',[ProfileController2::class,'nameChanging']);
        Route::post('deleteAccount',[ProfileController2::class,'deleteAccount']);

        Route::post('addStore',[StoreController2::class,'addStore']);
        Route::post('deleteStore',[StoreController2::class,'deleteStore']);
        Route::get('getStores',[StoreController2::class,'getStores']);
        Route::get('getStore',[StoreController2::class,'getStore']);
        Route::get('storeSearch',[StoreController2::class,'storeSearch']);

        Route::post('addProduct',[ProductController::class,'addProduct']);
        Route::post('deleteProduct',[ProductController::class,'deleteProduct']);
        Route::get('getProducts',[ProductController::class,'getProducts']);
        Route::get('getProduct',[ProductController::class,'getProduct']);
        Route::get('productSearch',[ProductController::class,'productSearch']);

        Route::post('addSuperClient',[SuperClientController::class,'addSuperClient']);
        Route::post('deleteSuperClient',[SuperClientController::class,'deleteSuperClient']);
        Route::get('getSuperClients',[SuperClientController::class,'getSuperClients']);
        Route::get('getSuperClient',[SuperClientController::class,'getSuperClient']);
        Route::get('superClientSearch',[SuperClientController::class,'superClientSearch']);

        Route::post('deleteClient',[ClientController::class,'deleteClient']);
        Route::get('getClients',[ClientController::class,'getClients']);
        Route::get('getClient',[ClientController::class,'getClient']);
        Route::get('clientSearch',[ClientController::class,'clientSearch']);

        Route::get('getClientCS',[CsController3::class,'getClientCS']);
        Route::get('getSuperClientCS',[CsController3::class,'getSuperClientCS']);

        Route::get('getOrders',[OrderController4::class,'getOrders']);
        Route::get('searchOrders',[OrderController4::class,'searchOrders']);
        Route::get('getOrder',[OrderController4::class,'getOrder']);
        Route::post('changeOrderStatus',[OrderController4::class,'changeOrderStatus']);

        Route::post('logout',[AuthController2::class,'logout']);
    });
});
