<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\UserModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    /**
     * 注册
     */
    public function reg(Request $request){
        $username = $request->input('username');
        $pwd = $request->input('pwd');
        $qpwd = $request->input('qpwd');
        $email = $request->input('email');
        $tel =intval($request->input('tel'));
        // var_dump($tel);die;
        
        if($pwd != $qpwd){
            $response = [
                'error' => 50001,
                'error' =>  '两次输入密码不一致'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        $password = password_hash($pwd,PASSWORD_BCRYPT);
        // var_dump($password);
        // 验证email唯一
        $user_email = UserModel::where(['email'=>$email])->first();
        if($user_email){
            $response = [
                'error' => 50002,
                'mag'   => '该邮箱已被注册'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        $data = [
            'username'  =>  $username,
            'password'  =>  $password,
            'email'     =>  $email,
            'tel'       =>  $tel,
            'add_time'  =>  time()
        ];
        $id = UserModel::insertGetId($data);
        if($id){
            $response = [
                'error' => 0,
                'msg'   => 'ok'
            ];
        }else{
            $response = [
                'error' => 50003,
                'msg'   => '注册失败'
            ];
        }
        die(json_encode($response,JSON_UNESCAPED_UNICODE));
    }
    /**
     * 登录
     */
    public function login(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');
        $user_Info = UserModel::where(['email'=>$email])->first();
        // echo password_verify($password,$user_Info['password']);
        if($user_Info){
            if(password_verify($password,$user_Info['password'])){
                $token = $this->getLoginToken($user_Info['id']);
                $response = [
                    'error' =>  0,
                    'msg'   =>  'ok',
                    'token' =>  $token
                ];
            }else{
                $response = [
                    'error' =>  50005,
                    'msg'   =>  '密码不正确'
                ];
            }
        }else{
            $reponse = [
                'error' =>  50004,
                'msg'   =>  '该用户不存在'
            ];
        }
        die(json_encode($response,JSON_UNESCAPED_UNICODE));
    }
    public function getLoginToken($uid){
        $key = "login_token:uid:".$uid;
        $token = Redis::get($key);
        if($token){
            return $token;
        }else{
            $login_token = substr(sha1(time().$uid.Str::random(10)),5,16);
            Redis::set($key,$token);
            Redis::expire($key,604800);
            return $login_token;
        }
    }
    // 用户中心
    public function my(){
        echo  $_SERVER['REMOTE_ADDR'];
        
    }
}