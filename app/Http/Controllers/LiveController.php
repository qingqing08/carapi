<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LiveController extends Controller
{
    //实景列表
    public function live_list(){
        $list = DB::table('broadcast')->get(['name' , 'image' , 'video' , 'url' , 'status']);
        if (empty($list)){
            $data = [];
        } else {
            foreach ($list as $key=>$value){
                $data[$key]['name'] = $value->name;
                $data[$key]['image'] = $this->image_url($value->image , 1);
                if ($value->status == 1){
                    $data[$key]['url'] = $this->image_url($value->video , 1);
                } else {
                    $data[$key]['url'] = $value->url;
                }
            }
        }

        return $this->returnAjax($data , '获取成功' , 200);
    }
}
