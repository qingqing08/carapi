<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('cors')->group(function () {
    //
});


//首页接口--轮播图、产品分类、智慧实训室、案例
Route::get('index' , 'IndexController@index');
//产品搜索
Route::get('productSearch' , 'IndexController@product_search');

////轮播图列表
//Route::get('banner' , 'IndexController@banner');

//产品分类列表
Route::get('productCategory' , "ProductController@product_category");
//产品列表
Route::get('productList' , "ProductController@product_list");

//智慧实训室和更多实训室
Route::get('trainingList' , 'IndexController@training_list');
//实训室详情
Route::get('trainingInfo' , 'ProductController@training_info');

//案例分类
Route::get('caseCategory' , 'CasesController@case_category');
//案例列表
Route::get('caseList' , 'CasesController@case_list');
//视频分类
Route::get('videoCategory' , 'VideoController@video_category');
//视频列表
Route::get('videoList' , 'VideoController@video_list');