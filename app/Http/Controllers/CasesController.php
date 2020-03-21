<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class CasesController extends Controller
{
    //案例分类
    public function case_category(){
        $list = DB::table('case_category')->get(['id' , 'category_name']);

        return $this->returnAjax($list , '获取成功' , 200);
    }

    //案例列表
    public function case_list(){
        $category_id = Input::get('category_id');
        if (empty($category_id)){
            $list = DB::table('case')->get(['id' , 'title' , 'image']);
        } else {
            $list = DB::table('case')->where('category_id' , $category_id)->get(['id' , 'title' , 'image']);
        }

        $list = $this->image_url($list , '2' , 'image');
        return $this->returnAjax($list , '获取成功' , 200);
    }

    //案例详情
    public function case_info(){
        $case_id = Input::get('case_id');
        $code = Input::get('code');
        if (empty($case_id)){
            return $this->returnAjax('' , '参数错误' , 100);
        }

        $info = DB::table('case')->where('id' , $case_id)->first(['id' , 'title' , 'content' , 'fabulous_number']);

        //评论列表
        $comment_list = DB::table('comment')->where(['data_id'=>$case_id , 'type'=>3])->orderBy('c_time' , 'desc')->get(['id' , 'content' , 'c_time']);
        $info->comment_number = count($comment_list);
        $info->comment_list = $comment_list;
        $info->tel = $this->tel();
        if (!empty($code)){
            $data = $this->wx_login($code);
            $info->user_id = $data->id;
        }

        if (empty($info)){
            return $this->returnAjax('' , '查无此数据' , 100);
        } else {
            return $this->returnAjax($info , '获取成功' , 200);
        }
    }
}
