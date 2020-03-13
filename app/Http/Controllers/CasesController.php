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
        if (empty($case_id)){
            return $this->returnAjax('' , '参数错误' , 100);
        }

        $info = DB::table('case')->where('id' , $case_id)->first(['id' , 'title' , 'content']);
        if (empty($info)){
            return $this->returnAjax('' , '查无此数据' , 100);
        } else {
            return $this->returnAjax($info , '获取成功' , 200);
        }
    }
}
