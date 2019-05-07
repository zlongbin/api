<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\UserModel;
class ApiController extends Controller
{
    //
    // 字符串
    public function str(Request $request){
        $data = $request->input();

        die(json_encode($data));
    }
    // 数组
    public function array(Request $request){
        $data = $request->input();
        // var_dump($post);die;
        // $userInfo = UserModel::where('nick_name',$post['nick_name'])->first();
        // if($userInfo){
        //     $data['error']='50001';
        //     $data['msg']='no';
        // }else{
        //     UserModel::insert($post);
        //     $data['error']=1;
        //     $data['msg']='ok';
        // }
        die(json_encode($data));
    }
    // json数据
    public function json(Request $request){
        $data = file_get_contents("php://input");
        die(json_encode($data));
    }
    public function restrict(){
        echo date("Y-m-d H:i:s");
    }
}
