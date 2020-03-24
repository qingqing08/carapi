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
//        $page = Input::get('page');

        $page = 1;
        $perage = 8;
        $limitprame = ($page -1) * $perage;
        if (empty($category_id)){
            $video_list = DB::table('videos')-> skip($limitprame)->take($perage)->get(['id' , 'video_name' , 'image' , 'watch_number']);
        } else {
            $video_list = DB::table('videos')->where('vc_id' , $category_id)-> skip($limitprame)->take($perage)->get(['id' , 'video_name' , 'image' , 'watch_number']);
        }

        foreach ($video_list as $key=>$value){
            $value->comment_number = DB::table('comment')->where(['data_id'=>$value->id , 'type'=>4])->count();
        }

        //主推视频列表
        $main_video = DB::table('video_main')->get(['data_id' , 'image']);
        $main_video = $this->image_url($main_video , '3' , 'image');
        $list['main_video'] = $main_video;

        //视频列表
        $list['video_list'] = $this->image_url($video_list , '3' , 'image');

        return $this->returnAjax($list , '获取成功' , 200);
    }

    //视频库详情
    public function video_info(){
        $video_id = Input::get('video_id');
        $code = Input::get('code');
//        $page = Input::get('page');
        if (empty($video_id)){
            return $this->returnAjax('' , '参数错误' , 100);
        }

        $info = DB::table('videos')->where('id' , $video_id)->first(['id' , 'video_name' , 'video' , 'introduction' , 'keyword' , 'watch_number' , 'share_number' , 'fabulous_number']);

        if (empty($info)){
            return $this->returnAjax('' , '查无此数据' , 100);
        } else {
            DB::table('videos')->increment('watch_number');
            $info->video = $this->image_url($info->video , 1);

//            echo $info->keyword;die;
            $ids[] = $video_id;
//            $list = DB::table('videos')->where('video_name' , 'like' , '%'.$info->keyword.'%')->whereNotIn('id' , $ids)->get(['id' , 'video_name' , 'image']);

            $page = 1;
            $perage = 4;
            $limitprame = ($page -1) * $perage;
            $list = DB::table('videos')->where('video_name' , 'like' , '%'.$info->keyword.'%')->whereNotIn('id' , $ids)-> skip($limitprame)->take($perage)->get(['id' , 'video_name' , 'image']);

            $count = count($list);
            if ($count){
                $list = $this->image_url($list , 3 , 'image');
                $info->relevant_video = $list;
            }

//            print_r($list);
            if ($count == 0){
                $new_list = DB::table('videos')->whereNotIn('id' , $ids)->limit(4)->get(['id' , 'video_name' , 'image']);
                $new_list = $this->image_url($new_list , 3 , 'image');
                $info->relevant_video = $new_list;
            }
//            $info->relevant_video = [];

//            unset($info->keyword);
            //评论列表
            $comment_list = DB::table('comment')->where(['data_id'=>$video_id , 'type'=>4])->orderBy('c_time' , 'desc')->get(['id' , 'content' , 'c_time']);
            $info->comment_number = count($comment_list);
            $info->comment_list = $comment_list;
            $info->tel = $this->tel();
//            if (!empty($code)){
//                $data = $this->wx_login($code);
//                $info->user_id = $data->id;
//            }
        }

        return $this->returnAjax($info , '获取成功' , 200);
    }

    //加载系列视频分页
    public function related_videos(){
        $video_id = Input::get('video_id');
        $page = Input::get('page');

        $info = DB::table('videos')->where('id' , $video_id)->first(['id' , 'video_name' , 'video' , 'introduction' , 'keyword' , 'watch_number' , 'share_number' , 'fabulous_number']);

        $ids[] = $video_id;
        $perage = 4;
        $limitprame = ($page -1) * $perage;
        $list = DB::table('videos')->where('video_name' , 'like' , '%'.$info->keyword.'%')->whereNotIn('id' , $ids)-> skip($limitprame)->take($perage)->get(['id' , 'video_name' , 'image']);

        if (!empty($list)){
            $data['list'] = $this->image_url($list , 3 , 'image');
        } else {
            $data['list'] = [];
        }

        $peraget = 4;
        $limitpramet = ($page) * $peraget;
        $data_list = DB::table('videos')->where('video_name' , 'like' , '%'.$info->keyword.'%')->whereNotIn('id' , $ids)-> skip($limitpramet)->take($peraget)->get(['id' , 'video_name' , 'image']);

        $count = count($data_list);
        $data['count'] = $count;

        return $this->returnAjax($data , '获取成功' , 200);
    }

    public function getlists($page , $perage , $table) {
        // 获取到当前currentpage 和perpage 每页多少条
        $limitprame = ($page -1) * $perage;
        $info = DB::table($table)-> skip($limitprame)->take($perage)-> get();

        $data = [
            'status'=> 1,
            'data' => [
                'data' => $info,
                'page' => $page
            ]
        ];

        return $data;
    }
}
