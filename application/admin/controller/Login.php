<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/5
 * Time: 14:19
 */
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class Login extends Controller {
    function index(){
        if(Session::has('userinfo'))
        {
            $this->redirect('index\index');
        }
        return $this->fetch();
    }
    function login(){
        if(!request()->isPost()){
            $this->redirect('index/index');
        }
        $name = input('post.username');
        $passwd = input('post.password');
        $captcha = input('post.captcha');

        if (!$name || !$passwd) {
            exit(json_encode(array('status' => 0, 'msg' => '用户名和密码不能为空')));

        }

        if(!captcha_check($captcha)){
            exit(json_encode(array('status' => 0, 'msg' => '请输入正确的验证码')));
        }

       $info = Db::name('admin')->where('username',$name)->find();
        $md5_passwd = md5($passwd);


        if (!$info || $md5_passwd != $info['password']) {
            exit(json_encode(array('status' => 0, 'msg' => '用户名或密码错误，请重新输入')));
        }

        if ($info['islock'] == 1) {
            exit(json_encode(array('status' => 0, 'msg' => '您的账户已被锁定，请联系超级管理员')));
        }

        //写入日志
        $data['ip'] = $login['loginip'] = request()->ip();
        $data['userid'] = $info['id'];
        $data['datetime'] = $login['logintime'] = time();
        $data['username'] =$name;
        $data['content']="{$name}登录后台";
        Db::name('log')->insert($data);
        Db::name('admin')->where('id',$info['id'])->update($login);

        //登入成功，存入session
        Session::set('userinfo',['name' => $name,'id' => $info['id'],'login_time' => time(),'user_type'=>$info['usertype']]);
        exit(json_encode(array('status' => 1, 'msg' => '登录成功', 'url' => url('index/index'))));/**/
    }

    function loginOut(){
        if( Session::has('userinfo'))
        {
            Session::clear();
            exit(json_encode((array('status' => 1, 'msg' => '退出成功','url'=>url('admin\login\index')))));
        }
        else{
            exit(json_encode((array('status' => 0, 'msg' => '退出失败'))));
        }
    }

}