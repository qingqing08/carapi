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
//实训室搜索
Route::get('trainingSearch' , 'IndexController@training_search');

//反馈接口
Route::post('feedback' , 'IndexController@feedback');
//公司信息
Route::get('companyInformation' , 'IndexController@company_information');
//联系我们
Route::get('contactUs' , 'IndexController@contact_us');
////轮播图列表
//Route::get('banner' , 'IndexController@banner');


/*---------------产品相关接口------------------------*/
//产品分类列表
Route::get('productCategory' , 'ProductController@product_category');
//产品列表
Route::get('productList' , 'ProductController@product_list');
//产品详情
Route::get('productInfo' , 'ProductController@product_info');


/*---------------实训室相关接口------------------------*/
//实训室分类
Route::get('trainingCategory' , 'ProductController@training_category');
//实训室列表
Route::get('trainingList' , 'ProductController@training_list');
//实训室详情
Route::get('trainingInfo' , 'ProductController@training_info');



/*---------------案例相关接口------------------------*/
//案例分类
Route::get('caseCategory' , 'CasesController@case_category');
//案例列表
Route::get('caseList' , 'CasesController@case_list');
//案例详情
Route::get('caseInfo' , 'CasesController@case_info');


/*---------------视频相关接口------------------------*/
//视频搜索
Route::get('videoSearch' , 'VideoController@video_search');
//视频分类
Route::get('videoCategory' , 'VideoController@video_category');
//视频列表
Route::get('videoList' , 'VideoController@video_list');
//视频详情
Route::get('videoInfo' , 'VideoController@video_info');