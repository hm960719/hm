<?php
namespace app\index\controller;
use think\Request;
use app\common\model\User;
use think\Db;
use think\Cookie;
use think\Controller;

class Login extends Controller{
    protected $noNeedLogin = ['login','loginapi'];
    /**
     * 账号登录
     */
    public function login()
    {   
        $password = Cookie::get('password');
        $account  = Cookie::get('account');

        $this->assign('password',$password);
        $this->assign('account',$account);
        return $this->view->fetch();
    }

    /**
     * 账号登出
     */
    public function loginout()
    {

    }

    public function loginapi()
    {
        $data = $_POST;
        $type = $data['type'];
        switch($type){
            case 'login':
                $account  = $data['account'];
                $password = md5($data['password']);
                if(empty($account) || empty($password)){
                    return json(['code'=>10001,'data'=>'请输入正确的账号密码']);
                }
                // return json(['code'=>300,'data'=>'123123123']);
                $where['account'] = $account;
                $userInfo = DB::table('user')->where($where)->find();
                
                if(empty($userInfo)) return json(['code'=>10002,'data'=>'用户不存在']);
                if($password != $userInfo['password']) return json(['code'=>10003,'data'=>'账号密码不正确']);

                Cookie::set('account',$account,360000000);
                Cookie::set('password',$data['password'],360000000);
                return json(['code'=>200]);
                break;
            case 'loginout':
                Cookie::set('account','');
                header("Location:/index/login/login");
                break;
            default:
                break;
        }
    }
    public function test()
    {
        echo 111;
    }
}