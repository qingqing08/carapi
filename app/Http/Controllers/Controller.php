<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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
        $url = "http://cmf.qc110.cn";
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
                        $val->$param = 'http://cmf.qc110.cn' . $val->$param;
                    }
                }
            }
        }

        return $data;
    }

}
