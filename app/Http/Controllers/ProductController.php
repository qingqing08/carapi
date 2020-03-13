<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ProductController extends Controller
{

    //产品分类
    public function product_category(){
        $list = DB::table('product_category')->get();

        return $this->returnAjax($list , '获取成功' , 200);
    }

    //产品列表
    public function product_list(){
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

    //产品详情
    public function product_info(){
        $product_id = Input::get('product_id');
        if (empty($product_id)){
            return $this->returnAjax('' , '参数错误' , 100);
        }

        $info = DB::table('product')->where('id' , $product_id)->first(['id' , 'product_name' , 'video' , 'images' , 'file' , 'content' , 'share_number' , 'fabulous_number']);
        if (!empty($info->video)){
            $info->video = $this->image_url($info->video , 1);
        }
        if (!empty($info->images)){
            $arr = explode(',' , $info->images);
            for ($i=0;$i<=count($arr)-1;$i++){
                $arr[$i] = $this->image_url($arr[$i] , 1);
            }

            $info->images = implode(',' , $arr);
        }

        $video_list = DB::table('product')->whereNotIn('id' , $product_id)->get(['id' , 'image']);
        $info->relevant_video = $video_list;

        return $this->returnAjax($info , '获取成功' , 200);
    }

    //实训室详情
    public function training_info(){
        $laboratory_id = Input::get('laboratory_id');

        if (empty($laboratory_id)) {
            return $this->returnAjax('' , '参数错误' , 100);
        }
        DB::table('laboratory')->increment('watch_number');

        $info = DB::table('laboratory')->where('id' , $laboratory_id)->first(['id' , 'laboratory_name' , 'video' , 'file' , 'content' , 'share_number' , 'fabulous_number']);
        if (!empty($info->image)){
            $info->image = $this->image_url($info->image , 1);
        }
        if (!empty($info->video)){
            $info->video = $this->image_url($info->video , 1);
        }
        if (!empty($info->file)){
            $info->file = $this->image_url($info->file , 1);
        }

        $list = DB::table('laboratory_video')->where('la_id' , $laboratory_id)->get(['id' , 'name']);

        $info->video_list = $list;

        return $this->returnAjax($info , '获取成功' , 200);
    }
}
