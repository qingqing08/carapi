<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class IndexController extends Controller
{
    //首页接口包含(轮播图--产品分类--实训室--院校案例)
    public function index(){
        //轮播图列表
        $banner_list = DB::table('banner')->orderBy('c_time' , 'desc')->get(['data_id' , 'type' , 'image']);
        $data['banner_list'] = $this->image_url($banner_list , 2 , 'image');

        //产品分类
        $product_category_list = DB::table('product_category')->orderBy('c_time' , 'desc')->get(['id' , 'name']);
        $data['product_category'] = $product_category_list;

        //实训室列表
        $laboratory_list = DB::table('laboratory')->where('is_wisdom' , 1)->orderBy('c_time' , 'desc')->get(['id' , 'laboratory_name' , 'image' , 'introduction']);
        $data['laboratory_list'] = $this->image_url($laboratory_list , 2 , 'image');

        //院校案例列表
        $case_list = DB::table('case')->orderBy('c_time' , 'desc')->get(['id' , 'title' , 'image']);
        $data['case_list'] = $this->image_url($case_list , '2' , 'image');

        return $this->returnAjax($data , '获取成功' , 200);
    }

    //首页搜索----根据产品名搜索
    public function product_search(){
        $condition = Input::get('condition');

        $list = DB::table('product')->where('product_name' , 'like' , '%'.$condition.'%')->get(['id' , 'product_name' , 'image']);
        $list = $this->image_url($list , '2' , 'image');

        return $this->returnAjax($list , '获取成功' , 200);
    }

    //首页搜索----根据实训室名搜索
    public function training_search(){
        $condition = Input::get('condition');

        if (empty($condition)){
            return $this->returnAjax('' , '搜索条件为空' , 100);
        }

        $list = DB::table('laboratory')->where('laboratory_name' , 'like' , '%'.$condition.'%')->get(['id' , 'laboratory_name' , 'introduction' , 'image']);
        $list = $this->image_url($list , '2' , 'image');

        return $this->returnAjax($list , '获取成功' , 200);
    }

    //信息反馈
    public function feedback(){
        $data = file_get_contents('php://input');

        $arr = json_decode($data , true);

        $preg_email='/^[a-zA-Z0-9]+([-_.][a-zA-Z0-9]+)*@([a-zA-Z0-9]+[-.])+([a-z]{2,5})$/ims';
        if(!preg_match($preg_email , $arr['email'])){
            return $this->returnAjax('' , '邮箱地址格式不正确' , 100);
        }

        $preg_phone='/^1[34578]\d{9}$/ims';
        if(!preg_match($preg_phone ,$arr['tel'])){
            return $this->returnAjax('' , '手机号格式不正确' , 100);
        }

        $arr['c_time'] = time();

        $result = DB::table('feedback')->insert($arr);
        if ($result){
            return $this->returnAjax('' , '反馈成功' , 200);
        } else {
            return $this->returnAjax('' , '操作失败' , 100);
        }

    }

    //公司介绍
    public function company_information(){
        $info = DB::table('company')->where('id' , 1)->first(['content']);

        return $this->returnAjax($info , '获取成功' , 200);
    }

    //联系我们
    public function contact_us(){
        $info = DB::table('company')->where('id' , 2)->first(['content']);

        return $this->returnAjax($info , '获取成功' , 200);
    }


    //轮播图
    public function banner(){
        $list = DB::table('banner')->get();

        $data = $this->image_url($list , 2 , 'image');
        return $this->returnAjax($data , '获取成功' , 200);
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
}
