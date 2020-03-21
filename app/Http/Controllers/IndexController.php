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
        $laboratory_category_list = DB::table('laboratory_category')->orderBy('c_time' , 'desc')->get(['id' , 'category_name' , 'name' , 'english_name']);
        $data['laboratory_category'] = $laboratory_category_list;

        //实训室列表
        $laboratory_list = DB::table('laboratory')->where('is_wisdom' , 1)->orderBy('c_time' , 'desc')->get(['id' , 'laboratory_name' , 'image' , 'introduction']);
        $data['laboratory_list'] = $this->image_url($laboratory_list , 2 , 'image');

        //院校案例列表
        $case_list = DB::table('case')->where('status' , 1)->orderBy('c_time' , 'desc')->get(['id' , 'title' , 'image']);
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

        if (empty($data_id)){
            return $this->returnAjax('' , '操作的数据为空' , 100);
        }
        if (empty($user_id)){
            return $this->returnAjax('' , '操作人为空' , 100);
        }
        $data = [
            'data_id'   =>  $data_id,
            'type'      =>  $type,
            'user_id'   =>  $user_id,
            'c_time'    =>  time(),
        ];

        $info = DB::table('fabulous')->where(['data_id'=>$data_id , 'type'=>$type , 'user_id'=>$user_id])->first();
        if (empty($info)){
            $result = DB::table('fabulous')->insert($data);
            if ($result){
                switch ($type){
                    case 1:
                        DB::table('product')->where('id' , $data_id)->increment('fabulous_number');
                        break;
                    case 2:
                        DB::table('laboratory')->where('id' , $data_id)->increment('fabulous_number');
                        break;
                    case 3:
                        DB::table('case')->where('id' , $data_id)->increment('fabulous_number');
                        break;
                    case 4:
                        DB::table('videos')->where('id' , $data_id)->increment('fabulous_number');
                        break;
                }
                return $this->returnAjax('' , '点赞成功' , 200);
            } else {
                return $this->returnAjax('' , '点赞失败' , 100);
            }
        } else {
            return $this->returnAjax('' , '不可以重复操作' , 100);
        }
    }

    //评论接口
    public function comment(){
        $str = file_get_contents("php://input");
        $data = json_decode($str , true);

        if (empty($data['data_id'])){
            return $this->returnAjax('' , '操作的数据为空' , 100);
        }
        if (empty($data['user_id'])){
            return $this->returnAjax('' , '操作人为空' , 100);
        }
        $data['c_time'] = time();

        $result = DB::table('comment')->insert($data);
        if ($result){
            return $this->returnAjax('' , '评论成功' , 200);
        } else {
            return $this->returnAjax('' , '评论失败' , 100);
        }
    }

    public function test(){
        $appid = "wxba5f6fdd47c6d644";
        $AppSecret = "6b10639a82cefcb8c1d83a6e9e5380a0";
        $redirect_uri = "http://api.jiaojumoxing.com/login";
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_base&state=123#wechat_redirect";

        return "<a href='".$url."'>点击登录</a>";
//        return $this->curlRequest($url);
//        return $this->wx_login();
    }

    public function login(){
        $code = Input::get('code');

        $data = $this->wx_login($code);
        return $this->returnAjax($data , '获取成功' , 200);
    }
}
