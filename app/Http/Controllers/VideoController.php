<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class VideoController extends Controller
{

    //视频搜索
    public function video_search(){
        $condition = Input::get('condition');
        if (empty($condition)){
            return $this->returnAjax('' , '搜索条件为空' , 100);
        }

        $list = DB::table('videos')->where('video_name' , 'like' , '%'.$condition.'%')->get(['id' , 'video_name' , 'image' , 'watch_number']);

        $list = $this->image_url($list , 3 , 'image');
        return $this->returnAjax($list , '获取成功' , 200);
    }

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

        //主推视频列表
        $main_video = DB::table('video_main')->get(['data_id' , 'image']);
        $main_video = $this->image_url($main_video , '3' , 'image');
        $list['main_video'] = $main_video;

        //维修视频列表
        $wx_list = $this->image_url($wx_list , '3' , 'image');
        //产品讲解列表
        $cp_list = $this->image_url($cp_list , '3' , 'image');
        $list['wx_list'] = $wx_list;
        $list['cp_list'] = $cp_list;
        return $this->returnAjax($list , '获取成功' , 200);
    }

    //视频库详情
    public function video_info(){
        $video_id = Input::get('video_id');
        if (empty($video_id)){
            return $this->returnAjax('' , '参数错误' , 100);
        }

        $info = DB::table('videos')->where('id' , $video_id)->first(['id' , 'video_name' , 'video' , 'introduction' , 'watch_number' , 'share_number' , 'fabulous_number']);
        if (empty($info)){
            return $this->returnAjax('' , '查无此数据' , 100);
        } else {
            DB::table('videos')->increment('watch_number');
            $info->video = $this->image_url($info->video , 1);

            $list = DB::table('videos')->whereNotIn('id' , $video_id)->get(['id' , 'video_name' , 'image']);
            $list = $this->image_url($list , 3 , 'image');
            $info->relevant_video = $list;
        }

        return $this->returnAjax($info , '获取成功' , 200);
    }
}
