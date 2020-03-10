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


//轮播图列表
Route::get('banner' , 'IndexController@banner');
//产品分类列表
Route::get('productCategory' , "IndexController@product_category");
//产品列表
Route::get('productList' , "IndexController@product");
//智慧实训室和更多实训室
Route::get('trainingList' , 'IndexController@training_list');
//实训室详情
Route::get('trainingInfo' , 'IndexController@training_info');
//案例分类
Route::get('caseCategory' , 'CacesController@case_category');