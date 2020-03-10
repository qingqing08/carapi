<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class IndexController extends Controller
{
    //轮播图
    public function banner(){
        $list = DB::table('banner')->get();

        $data = $this->image_url($list , 2 , 'image');
        return $this->returnAjax($data , '获取成功' , 200);
    }

    //产品分类
    public function product_category(){
        $list = DB::table('product_category')->get();

        return $this->returnAjax($list , '获取成功' , 200);
    }

    //产品列表
    public function product(){
        $category_id = Input::get('category_id');

        if (empty($category_id)){
            $list = DB::table('product')->get();
        } else {
            $list = DB::table('product')->where('category_id' , $category_id)->get();
        }
        foreach ($list as $key=>$val){
            $val->image = 'http://cmf.qc110.cn' . $val->image;
            $arr = explode(",", $val->images);
            for ($i=0;$i<=count($arr)-1;$i++){
                $newarr[] = 'http://cmf.qc110.cn' . $arr[$i];
            }
            $val->images = implode(',' ,$newarr);
            $val->video = 'http://cmf.qc110.cn' . $val->video;
            $val->file = 'http://cmf.qc110.cn' . $val->file;
        }

        return $this->returnAjax($list , '获取成功' , 200);
    }

    //智慧实训室和更多实训室
    //参数传值为智慧实训室--不传值为更多实训室
    public function training_list(){
        $is_wisdom = Input::get('is_wisdom');

        if (!empty($is_wisdom)) {
            $list = DB::table('laboratory')->where('is_wisdom' , $is_wisdom)->get(['id' , 'laboratory_name' , 'image' , 'introduction']);
        } else {
            $list = DB::table('laboratory')->get(['id' , 'laboratory_name' , 'image' , 'introduction']);
        }

        $data = $this->image_url($list , 2 , 'image');

        return $this->returnAjax($data , '获取成功' , 200);
    }

    //实训室详情
    public function training_info(){
        $laboratory_id = Input::get('laboratory_id');

        if (empty($laboratory_id)) {
            return $this->returnAjax('' , '参数错误' , 100);
        }
        DB::table('laboratory')->increment('watch_number');

        $url = 'http://cmf.qc110.cn';
        $info = DB::table('laboratory')->where('id' , $laboratory_id)->first();
        $info->image = $url.$info->image;
        $info->video = $url.$info->video;
        $info->file = $url.$info->file;
        $list = DB::table('laboratory_video')->where('la_id' , $laboratory_id)->get(['id' , 'name']);

        $info->video_list = $list;

        return $this->returnAjax($info , '获取成功' , 200);
    }

    //点赞接口
    public function fabulous(){
        $data_id = Input::get('data_id');
        $type = Input::get('type');
        $user_id = Input::get('user_id');

        switch ($type){
            case 1:
                return 111;
        }
    }

    //首页案例列表
    public function case_list(){
        $list = DB::table('case')->get(['id' , 'title' , 'image']);

        $data = $this->image_url($list , '2' , 'image');
        return $this->returnAjax($data , '获取成功' , 200);
    }

}
