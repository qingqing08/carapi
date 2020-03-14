<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CameraController extends Controller
{
    //开始操作
    public function start(){
        $direction = Input::get('direction');
//        $url = "https://open.ys7.com/api/lapp/device/ptz/start?accessToken=at.81s7eo1c2546lanv6vngbpsbbmth8b90-99ri2u5up4-0ieehsk-xmbfktkf9&deviceSerial=D85575907&channelNo=1&direction=".$direction."&speed=2";
        $url = "https://open.ys7.com/api/lapp/device/ptz/start";
        $data = [
            'accessToken'   => "at.81s7eo1c2546lanv6vngbpsbbmth8b90-99ri2u5up4-0ieehsk-xmbfktkf9",
            'deviceSerial'  => "D85575907",
            'channelNo'  => "1",
            'direction'  => $direction,
            'speed'      => 2,
        ];
        $result = $this->curlRequest($url , $data);

        return $result;
    }

    //停止操作
    public function stop(){
        $direction = Input::get('direction');
//        $url = "https://open.ys7.com/api/lapp/device/ptz/stop?accessToken=at.81s7eo1c2546lanv6vngbpsbbmth8b90-99ri2u5up4-0ieehsk-xmbfktkf9&deviceSerial=D85575907&channelNo=1&direction=".$direction;
        $url = "https://open.ys7.com/api/lapp/device/ptz/stop";
        $data = [
            'accessToken'   => "at.81s7eo1c2546lanv6vngbpsbbmth8b90-99ri2u5up4-0ieehsk-xmbfktkf9",
            'deviceSerial'  => "D85575907",
            'channelNo'  => "1",
            'direction'  => $direction,
        ];
        $result = $this->curlRequest($url , $data);

        return $result;
    }
}
