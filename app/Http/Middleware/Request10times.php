<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class Request10times
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
        $ip = $_SERVER['REMOTE_ADDR'];
        $uid = $request->input('uid');
        $key = $uid."Request10times".$ip;
        $num = Redis::get($key);
        if($num>10){
            die('超过次数限制');
        }
        // echo $ip;   echo "<br>";
        // echo $num;  echo "<br>";
        Redis::incr($key);
        Redis::expire($key,5);

        return $next($request);
    }
}
