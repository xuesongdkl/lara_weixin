<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class WxUserModel extends Model
{
    public $table='p_wx_users';
    public $timestamps=false;
    public  static $redis_weixin_access_token='str:weixin:access_token';   //微信access_token

    /**
     * 获取access_token
     */
    public  static function getAccessToken()
    {
        //获取缓存
        $token = Redis::get(self::$redis_weixin_access_token);
        if(!$token){        // 无缓存 请求微信接口
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WX_APPID').'&secret='.env('WX_APPSECRET');
//            echo $url;die;
            $data = json_decode(file_get_contents($url),true);
            //写入缓存
            $token = $data['access_token'];
            Redis::set(self::$redis_weixin_access_token,$token);
            //设置过期时间
            Redis::setTimeout(self::$redis_weixin_access_token,3600);
        }
        return $token;

    }
}
