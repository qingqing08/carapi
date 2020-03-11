<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class VideoController extends Controller
{
    //视频分类列表
    public function video_category(){
        $list = DB::table('video_category')->get(['id' , 'name']);

        return $this->returnAjax($list , '获取成功' , 200);
    }

    //视频列表
    public function video_list(){
        $category_id = Input::get('category_id');
        $list = DB::table('videos')->where('vc_id' , $category_id)->get(['id' , 'video_name']);

        $data = $this->image_url($list , '2' , 'video');
        return $this->returnAjax($data , '获取成功' , 200);
    }
}
