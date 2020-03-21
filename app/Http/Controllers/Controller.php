<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /* *
     * 统一返回格式
     * $arr需要返回的数据
     * $msg返回成功与否的信息
     * $code状态码200为成功100为失败
     * */
    public function returnAjax($arr , $msg = "" , $code){
        $data['code'] = $code;
        $data['msg'] = $msg;
        $data['data'] = $arr;
        return $data;
    }

    /* *
     * 给图片地址重新赋值
     * data数组
     * type类型1单个图片赋值2数组循环赋值
     * param 需要重新复制的字段名
     */
    public function image_url($data , $type=1 , $param = ''){
        $url = "http://cmf.jiaojumoxing.com";
        if ($data != ''){
            if ($type == 1){
                $data = $url.$data;
            }
            if ($type == 2){
                foreach ($data as $key=>$val){
                    $val->$param = $url.$val->$param;
                }
            }

            if ($type == 3){
                foreach ($data as $key=>$val){
                    if ($val->$param != '') {
                        $val->$param = $url . $val->$param;
                    }
                }
            }
        }

        return $data;
    }

    public function tel(){
        $tel = DB::table('tel')->where('id' , 1)->value('tel');
        return $tel;
    }

    public function wx_login($code){
//        echo $code;
        $appid = "wxba5f6fdd47c6d644";
        $AppSecret = "6b10639a82cefcb8c1d83a6e9e5380a0";
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$AppSecret."&code=".$code."&grant_type=authorization_code";

        $access_token = $this->curlRequest($url);
//        echo $access_token;
        $arr = json_decode($access_token , true);


//        print_r($arr);die;
        $token = $arr['access_token'];

        $user_url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$token."&openid=OPENID&lang=zh_CN";

        $json_user_info = $this->curlRequest($user_url);
        $data = json_decode($json_user_info , true);
        $user_data = [
            'openid' => $data['openid'],
            'nickname'  =>  urlencode($data['nickname']),
            'sex'   =>  $data['sex'],
            'address'  =>  $data['province'].$data['city'],
            'headimgurl'    =>  $data['headimgurl'],
            'c_time'    =>  time(),
        ];

        $user_info = DB::table('wx_user')->where('openid' , $data['openid'])->first();
        if (empty($user_info)) {
            $result = DB::table('wx_user')->insert($user_data);
            if ($result) {
                $user_info = DB::table('wx_user')->where('openid', $data['openid'])->first();
            }
        }

        $user_info->nickname = urldecode($user_info->nickname);
        return $user_info;
    }



    /**
    使用curl方式实现get或post请求
    @param $url 请求的url地址
    @param $data 发送的post数据 如果为空则为get方式请求
    return 请求后获取到的数据
     */
    function curlRequest($url,$data = ''){
        $ch = curl_init();
        $params[CURLOPT_URL] = $url;    //请求url地址
        $params[CURLOPT_HEADER] = false; //是否返回响应头信息
        $params[CURLOPT_RETURNTRANSFER] = true; //是否将结果返回
        $params[CURLOPT_FOLLOWLOCATION] = true; //是否重定向
        $params[CURLOPT_TIMEOUT] = 30; //超时时间
        if(!empty($data)){
            $params[CURLOPT_POST] = true;
            $params[CURLOPT_POSTFIELDS] = $data;
        }
        $params[CURLOPT_SSL_VERIFYPEER] = false;//请求https时设置,还有其他解决方案
        $params[CURLOPT_SSL_VERIFYHOST] = false;//请求https时,其他方案查看其他博文
        curl_setopt_array($ch, $params); //传入curl参数
        $content = curl_exec($ch); //执行
        curl_close($ch); //关闭连接
        return $content;
    }

}
