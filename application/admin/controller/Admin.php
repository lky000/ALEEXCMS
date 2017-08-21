<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/10
 * Time: 16:15
 */
namespace app\admin\controller;
use think\Config;
use think\Controller;
use think\Db;
use think\Exception;
use think\Request;
use think\Cache;
use think\Session;
class Admin extends Common {
    function index(){
        if(Session::get("userinfo.user_type")>2)
        { $adminInfo=Db::name('admin')->select();
        $this->assign("adminInfo",$adminInfo);

        return $this->fetch();}
    else
    {
        return $this->fetch('\noAccess');
    }
    }
    function add(){
        if(!request()->isPost())
        {return $this->fetch();}
        else
        {
            $data=array();
            $data['username'] = input('post.username');

            $data['password'] = md5(input('post.password'));
            $data['encrypt'] = "";//6位hash值
            $data['realname'] = '';
            $data['email'] = input('post.email');
            $data['usertype']="0";
            $data['logintime'] = time();
            $data['loginip'] = request()->ip();
            $data['islock']='0';
            $data['createtime'] = time();

            $res=Db::name('admin')->insert($data);
            if($res)
            {
                addLog(2,'添加了管理员');
                exit(json_encode(["status"=>1,"msg"=>'添加成功',"url"=>url('admin/index')]));
            }
            else
            {exit(json_encode(['status'=>0,"msg"=>'添加失败']));
                }
        }
    }
    function edit($id){
        if(request()->isGet())
        {
            $userType=Db::name('usertype')->select();
            $adminInfo=Db::name('admin')->find($id);
            $this->assign('userType',$userType);
            $this->assign('adminInfo',$adminInfo);
            return $this->fetch();
        }
        else{




                  $data[ "username"]=input("post.username");

                  $data["email"]=input("post.email");
                  $data["password"]=input("post.password");
                  $data["islock"]=input("post.islock");
                  $data["usertype"]=input("post.usertype");


              $res=Db::name('admin')->where('id',$id)->update($data);

              if($res){
                  addLog(2,'修改了管理员信息');
                  exit(json_encode(['msg'=>'修改成功','status'=>1,'url'=>url('admin/index')]));
              }
              else
              {
                  exit(json_encode(['msg'=>'修改失败','status'=>0]));
              }
        }
    }
function check_psw($id){

        $psw=md5(input("post.psw"));
        $res=Db::name('admin')->where(['id'=>$id,'password'=>$psw])->find();
        if($res){exit('success');}
        else{exit('failed');}
}
    function dele(){
$data=input("get.id");
$res=Db::name('admin')->delete($data);
if($res){
    addLog(2,'删除了管理员信息');
    exit('删除成功！');}
else{exit('删除失败！');}
    }
}