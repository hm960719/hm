<?php

namespace app\index\controller;
use think\Cookie;
use think\Controller;
class Check extends Controller{
    
    public function _initialize()
    {
        $this->check();
    }

    /**
     * 鉴权
     */
    public function check()
    {
        $account  = Cookie::get('account');
        $password = Cookie::get('password');
        // $domain   = config('domain');
        if(empty($account)){
            header("Location: /index/login/login");
        }
    }
}