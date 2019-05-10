<?php

namespace app\index\controller;

use think\Request;
use app\common\model\User;
use think\Db;
use think\Cookie;
use think\Controller;
use app\index\controller\Check;

class Users extends Check{
    /**
     * 会员注册
     */
    public function _initialize()
    {
        parent::_initialize();
    }


    public function register(Request $request)
    {
        if($_POST){
            $_POST['account']     = intval($_POST['account']);
            $_POST['jie_account'] = intval($_POST['jie_account']);
            $_POST['tui_account'] = intval($_POST['tui_account']);
            $_POST['ser_account'] = intval($_POST['ser_account']);
            $_POST['create_time'] = date("Y-m-d H:i:s",time());
            $_POST['password']    = md5(123456);
            foreach($_POST as $k => $v){
                if(empty($v)){
                    return json(['code'=>4001,'data'=>'参数缺失']);
                }
            }
            if(!preg_match("/^1[34578]\d{9}$/", $_POST['phone'])){
                return json(['code'=>4002,'data'=>'手机号输入错误']);
            }
            if(!preg_match("/^1[34578]\d{9}$/", $_POST['receive_phone'])){
                return json(['code'=>4002,'data'=>'收货人手机号输入错误']);
            }
            if(!preg_match("/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/",$_POST['idcode'])){
                return json(['code'=>4002,'data'=>'身份证号输入错误']);
            }
            $ckaccount = DB::table('user')->where(['account'=>$_POST['account']])->find();
            if(!empty($ckaccount)){
                return json(['code'=>4002,'data'=>'该账号已被使用,请刷新重试']);
            }
            $res = DB::table('user')->insert($_POST);
            if($res){
                return json(['code'=>200,'data'=>'注册成功']);
            }else{
                return json(['code'=>4001,'data'=>'注册失败,请检查信息']);
            }
            
        }
        $account_id = mt_rand(10000000,99999999);
        $this->assign('account_id',$account_id);
        return $this->view->fetch();
    }

    /**
     * 物流信息
     */

    public function logistics()
    {
        $where = [];
        $order = "id asc";
        if($_GET){
            // dump($_POST);die;
            $id   = !empty($_GET['account']) ? $_GET['account'] :'';
            $name = !empty($_GET['name']) ? $_GET['name'] :'';
            if(!empty($id)){
                $where['account'] = $id;
            }
            if(!empty($name)){
                $where['name'] = $name;
            }
        }
        
        $list = DB::table('logistics')->where($where)->order($order)->paginate(10,false,['query'=>$where]);
        $this->assign(['name'=>$name,'account'=>$id]);
        $this->assign('list',$list);
        return $this->view->fetch();
    }

    /**
     * 部门业绩
     */

     public function performance()
     {
         $where = [];
         $order = "id asc";
         if($_GET){
            $id   = !empty($_GET['account']) ? $_GET['account'] :'';
            if(!empty($id)){
                $where['account'] = $id;
            }
         }
         $list = DB::table('yeji')->where($where)->order($order)->paginate(10,false,['query'=>$where]);
         $this->assign(['account'=>$id]);
         $this->assign('list',$list);
         return $this->view->fetch();
         return $this->fetch();
     }

     /**
      * 推荐管理
      */
      public function tuiadmin()
      {
        $where = [];
        $order = "id asc";
        if($_GET){
            $id   = !empty($_GET['account']) ? $_GET['account'] :'';
            if(!empty($id)){
                $where['account'] = $id;
            }
        }
        $list = DB::table('tui_admin')->where($where)->order($order)->paginate(10,false,['query'=>$where]);
        $this->assign(['account'=>$id]);
        $this->assign('list',$list);
        return $this->view->fetch();
      }

      /**
       * 奖金查询
       */
      public function rewardlist()
      {
        $where = [];
        $order = "time asc";
        if($_GET){
            $time   = !empty($_GET['time']) ? $_GET['time'] :'';
            if(!empty($time)){
                $where['time'] = $time;
            }
        }
        $list = DB::table('rewardlist')->where($where)->order($order)->paginate(10,false,['query'=>$where]);
        $this->assign(['time'=>$time]);
        $this->assign('list',$list);
        return $this->view->fetch();
      }
}