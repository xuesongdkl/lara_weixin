<?php

namespace App\Http\Controllers\Weixin;

use App\Model\WxUserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;

class WxController extends Controller
{

    /**
     * 获取微信用户信息
     */
    public function getUserInfo($openid){
        $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.WxUserModel::getAccessToken().'&openid='.$openid.'&lang=zh_CN';
        $data=json_decode(file_get_contents($url),true);
        echo "<pre>";print_r($data);echo "</pre>";
    }

    /**
     * 获取微信用户标签
     */
    public function getWxTags(){
        $url='https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.WxUserModel::getAccessToken();
        $data=json_decode(file_get_contents($url),true);
        echo "<pre>";print_r($data);echo "</pre>";
    }

    /**
     * 创建微信用户标签
     */
    public function createWxTags($name=null){
        $url='https://api.weixin.qq.com/cgi-bin/tags/create?access_token='.WxUserModel::getAccessToken();
        $client=new Client();
        $data=[
            'tag'    =>    [
                'name'   =>   'bbb标签'
            ]
        ];
        $r = $client->request('POST', $url, [
            'body' => json_encode($data,JSON_UNESCAPED_UNICODE)
        ]);
        echo $r->getBody();
    }
}
