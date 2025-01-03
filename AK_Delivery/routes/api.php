<?php

use App\Http\Controllers\admin_controller\AuthController2;
use App\Http\Controllers\admin_controller\ProfileController2;
use App\Http\Controllers\client_controller\AuthController;
use App\Http\Controllers\client_controller\OrderController;
use App\Http\Controllers\client_controller\OrderProductsController;
use App\Http\Controllers\client_controller\ProfileController;
use App\Http\Controllers\client_controller\StoreController;
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

        Route::post('logout',[AuthController::class,'logout']);
    });
});

//Route::group(['prefix' => 'superClient'], function () {
//    Route::post('register',[AuthController::class,'register']);
//    Route::post('login',[AuthController::class,'login']);
//    Route::middleware(['auth:sanctum'])->group(function () {
//        Route::get('getProfile', function (Request $request) {
//            return $request->user();
//        });
////        Route::post('deleteAccount',[StudentProfileController::class,'deleteAccount']);
////
////        Route::get('getSubjects',[Student_Course_Controller::class,'getSubjects']);
////        Route::get('getCourses/{id}',[Student_Course_Controller::class,'getCourses']);
////        Route::post('get_course',[Student_Course_Controller::class,'get_course']);
////        Route::post('get_teacher_information',[Student_Course_Controller::class,'get_teacher_information']);
////        Route::post('enroll_Courses',[Student_Course_Controller::class,'enroll_Courses']);
////        Route::get('course_addRating',[Student_Course_Controller::class,'course_addRating']);
////        Route::get('getCourseRating',[Student_Course_Controller::class,'getCourseRating']);
////        Route::post('getCourses',[Student_Course_Controller::class,'getCourses']);
////
////        Route::post('show_course_sections',[Student_Course_Section_Controller::class,'show_course_sections']);
////        Route::post('get_course_section',[Student_Course_Section_Controller::class,'get_course_section']);
////        Route::post('get_video',[Student_Course_Section_Controller::class,'get_video']);
////        Route::post('addComment',[Student_Course_Section_Controller::class,'addComment']);
////        Route::post('getComments',[Student_Course_Section_Controller::class,'getComments']);
////        Route::post('delete_comment',[Student_Course_Section_Controller::class,'delete_comment']);
////        Route::get('getFile',[Student_Course_Section_Controller::class,'getFile']);
////        Route::get('getExams',[Student_Course_Section_Controller::class,'getExams']);
////        Route::post('addAnswer',[Student_Course_Section_Controller::class,'addAnswer']);
////        Route::get('getStudentMark',[Student_Course_Section_Controller::class,'getStudentMark']);
////
////        Route::get('continueLearning',[StudentHomeController::class,'continueLearning']);
////        Route::post('add_complaints_suggestions',[StudentHomeController::class,'add_complaints_suggestions']);
////        Route::get('search_TeacherName',[StudentHomeController::class,'search_TeacherName']);
//
//        Route::post('logout',[AuthController::class,'logout']);
//    });
//});



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

        Route::post('logout',[AuthController2::class,'logout']);
    });
});
