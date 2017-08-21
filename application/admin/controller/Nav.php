<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8
 * Time: 23:16
 */
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Config;
use think\Hook;
class Nav extends Common {

    function index(){
        return $this->fetch();
    }
    function add(){
        if(!request()->isPost())
        {
            $model = Db::name('model')->field('id,name')->select();
            $this->assign('model', $model);
            return $this->fetch();
        }
        else{
                 $data=input("post.");

                     $res=Db::name('category')->insert($data);
                     if($res){
                         addLog(2,'添加了自定义导航');
                         exit(json_encode(array("status"=>1,"msg"=>'添加成功',"url"=>url('nav/index'))));
                     }
                     else
                     {
                         exit(json_encode(array('status'=>0,'msg'=>'添加失败')));
                     }


        }
    }

    function edit($id){
if(!request()->isPost())
{        $model = Db::name('model')->field('id,name')->select();
        $this->assign('model', $model);
        $data=Db::name('category')->find($id);
        $this->assign('data',$data);

        return $this->fetch();

    }
    else
    {
        $data=input("post.");

        $flag = Db::name('category')->where(['id' => $id])->update($data);
        if ($flag) {
            addLog(2,'修改了自定义导航');
            exit(json_encode(['status' => 1, 'msg' => '修改栏目成功','url' => url('nav/index')]));
        }else{
            exit(json_encode(['status' => 0, 'msg' => '修改栏目失败','url' => url('nav/index')]));
        }
    }
    }

    function dele($id){
        $res=Db::name('category')->where('id',$id)->delete();
        if($res) {
            addLog(2,'删除了自定义导航');
          echo '删除成功！';
        }
        else{
            echo '删除失败！';
        }
    }
}