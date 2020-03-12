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

        //维修视频列表
        $wx_list = $this->image_url($wx_list , '2' , 'image');
        //产品讲解列表
        $cp_list = $this->image_url($cp_list , '2' , 'image');
        $list['wx_list'] = $wx_list;
        $list['cp_list'] = $cp_list;
        //主推视频列表
        $main_video = DB::table('video_main')->get(['data_id' , 'image']);
        $main_video = $this->image_url($main_video , '2' , 'image');
        $list['main_video'] = $main_video;
        return $this->returnAjax($list , '获取成功' , 200);
    }
}
