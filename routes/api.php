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

Route::post("mentors","MentorController@create");
Route::put("mentors/{id}","MentorController@update");
Route::get("mentors/{id}","MentorController@show");
Route::get("mentors","MentorController@index");

Route::delete("mentors/{id}","MentorController@destroy");
Route::post("courses","CourseController@create");
Route::put("courses/{id}","CourseController@update");
Route::get("courses","CourseController@index");
Route::get("courses/{id}","CourseController@show");
Route::delete("courses/{id}","CourseController@destroy");

Route::post("chapters","ChapterController@create");
Route::put("chapters/{id}","ChapterController@update");
Route::get("chapters","ChapterController@index");
Route::get("chapters/{id}","ChapterController@show");
Route::delete("chapters/{id}","ChapterController@destroy");

Route::post("lessons","lessonController@create");
Route::put("lessons/{id}","lessonController@update");
Route::get("lessons","lessonController@index");
Route::get("lessons/{id}","lessonController@show");
Route::delete("lessons/{id}","lessonController@destroy");


Route::post("image-courses","imageController@create");
Route::delete("image-courses/{id}","imageController@destroy");

Route::post("my-courses","MyCourseController@create");
Route::get("my-courses","MyCourseController@index");
Route::post("my-courses/premium","MyCourseController@createPremiumAcces");
Route::post("reviews","reviewController@create");
Route::put("reviews/{id}","reviewController@update");
Route::delete("reviews/{id}","reviewController@delete");


