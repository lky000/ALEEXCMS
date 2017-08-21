<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/6
 * Time: 16:52
 */
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\Request;
use app\admin\model\Admin;

class Register extends Controller {

    function register(){
       return $this->fetch();
    }
    function regis()
    {
        if (!request()->isPost()) {
            $this->redirect('index/index');
        }
        $name = input('post.username');
        $passwd = md5(input('post.password'));
        $realname = input('post.realname');
        $email = input('post.email');

        if (!$name || !$passwd) {
            exit(json_encode(array('status' => 0, 'msg' => '用户名和密码不能为空')));

        }
$data=array();
   $data['username'] = $name;

         $data['password'] = $passwd;
        $data['encrypt'] = "";//6位hash值
       $data['realname'] = $realname;
         $data['email'] = $email;
        $data['usertype']="0";
        $data['logintime'] = time();
        $data['loginip'] = request()->ip();
        $data['islock']='0';
        $data['createtime'] = time();
//        insert into mycms_admin VALUEs(null,{$data['username']},'{$data['password']}','','{$data['realname']}','{$data['email']}','cc',0,0,'0','0.0.0.0');/

//    $res =Db::execute("insert into mycms_admin VALUEs(null,'{$data['username']}','{$data['password']}','','{$data['realname']}','{$data['email']}','{$data['usertype']}','{$data['logintime']}','{$data['loginip']}','{$data['islock']}','{$data['createtime']}');");
$res=Db::name("admin")->insert($data);
        if (!$res) {
            exit(json_encode(array('status' => 0, 'msg' => '注册失败！')));
        }

exit(json_encode(array('status' => 1, 'msg' => '注册成功', 'url' => url('login/index'))));
//        exit(json_encode(array('status' => 0, 'msg' => $data)));



    }
}