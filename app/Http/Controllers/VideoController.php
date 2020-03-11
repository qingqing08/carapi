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
        if (empty($category_id)){
            $wx_list = DB::table('videos')->where('type' , 1)->get(['id' , 'video_name' , 'image' , 'watch_number']);
            $cp_list = DB::table('videos')->where('type' , 2)->get(['id' , 'video_name' , 'image' , 'watch_number']);
        } else {
            $wx_list = DB::table('videos')->where(array('vc_id'=>$category_id , 'type'=>1))->get(['id' , 'video_name' , 'image' , 'watch_number']);
            $cp_list = DB::table('videos')->where(array('vc_id'=>$category_id , 'type'=>2))->get(['id' , 'video_name' , 'image' , 'watch_number']);
        }

        $list['wx_list'] = $wx_list;
        $list['cp_list'] = $cp_list;
        $data = $this->image_url($list , '2' , 'image');
        return $this->returnAjax($data , '获取成功' , 200);
    }
}
