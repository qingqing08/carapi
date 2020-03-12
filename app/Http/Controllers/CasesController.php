<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CasesController extends Controller
{
    //案例分类
    public function case_category(){
        $list = DB::table('case_category')->get(['id' , 'category_name']);

        return $this->returnAjax($list , '获取成功' , 200);
    }
}
