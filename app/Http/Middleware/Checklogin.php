<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class Checklogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->input('token');
        $uid = $request->input('uid');
        if(empty($token) || empty($uid)){
            $response=[
                'error' =>  50006,
                'msg'   =>  '参数不完整'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        $key = "login_token:uid:".$uid;
        $local_token=Redis::get($key);
        if($local_token){
            if($token == $local_token){
                //TODO 记录日志
                $str = date('Y-m-d H:i:s').">>>>:token:".$token.":uid:".$uid."\n";
                file_put_contents("logs/checklogin.log",$str,FILE_APPEND);
            }else{
                $response = [
                    'error' => 50008,
                    'msg'   => 'token值过期'
                ];
                die(json_encode($response,JSON_UNESCAPED_UNICODE));
            }
        }else{
            $response = [
                'error' => 50007,
                'msg'   =>'未授权'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        return $next($request);
    }
}
