<?php

use App\Http\Controllers\client_controller\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::group(['prefix' => 'client'], function () {
    Route::post('register',[AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('getProfile', function (Request $request) {
            return $request->user();
        });
//        Route::post('deleteAccount',[StudentProfileController::class,'deleteAccount']);
//
//        Route::get('getSubjects',[Student_Course_Controller::class,'getSubjects']);
//        Route::get('getCourses/{id}',[Student_Course_Controller::class,'getCourses']);
//        Route::post('get_course',[Student_Course_Controller::class,'get_course']);
//        Route::post('get_teacher_information',[Student_Course_Controller::class,'get_teacher_information']);
//        Route::post('enroll_Courses',[Student_Course_Controller::class,'enroll_Courses']);
//        Route::get('course_addRating',[Student_Course_Controller::class,'course_addRating']);
//        Route::get('getCourseRating',[Student_Course_Controller::class,'getCourseRating']);
//        Route::post('getCourses',[Student_Course_Controller::class,'getCourses']);
//
//        Route::post('show_course_sections',[Student_Course_Section_Controller::class,'show_course_sections']);
//        Route::post('get_course_section',[Student_Course_Section_Controller::class,'get_course_section']);
//        Route::post('get_video',[Student_Course_Section_Controller::class,'get_video']);
//        Route::post('addComment',[Student_Course_Section_Controller::class,'addComment']);
//        Route::post('getComments',[Student_Course_Section_Controller::class,'getComments']);
//        Route::post('delete_comment',[Student_Course_Section_Controller::class,'delete_comment']);
//        Route::get('getFile',[Student_Course_Section_Controller::class,'getFile']);
//        Route::get('getExams',[Student_Course_Section_Controller::class,'getExams']);
//        Route::post('addAnswer',[Student_Course_Section_Controller::class,'addAnswer']);
//        Route::get('getStudentMark',[Student_Course_Section_Controller::class,'getStudentMark']);
//
//        Route::get('continueLearning',[StudentHomeController::class,'continueLearning']);
//        Route::post('add_complaints_suggestions',[StudentHomeController::class,'add_complaints_suggestions']);
//        Route::get('search_TeacherName',[StudentHomeController::class,'search_TeacherName']);

        Route::post('logout',[AuthController::class,'logout']);
    });
});
