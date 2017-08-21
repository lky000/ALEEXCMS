<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/12
 * Time: 16:10
 */

namespace app\admin\controller;

use think\Controller;
use think\Db;

class Page extends Common
{
    function index()
    {
        $pages = Db::name('category')->field('name,ename,id')->where('modelid', 2)->select();
        $this->assign('pages', $pages);
        return $this->fetch();
    }

    function add()
    {
        if (!request()->isPost()) {

            return $this->fetch();
        } else {
          $data['modelid']=input('post.modelid');
          $data['name']=input('post.name');
          $data['ename']=input('post.ename');
          $data['description']=input('post.description');
          $data['content']=input('post.content');
          $res=Db::name('category')->insert($data);
          if($res){
              addLog(2,'添加了单页面');
              exit(json_encode(['msg'=>'添加成功','status'=>1,'url'=>url('page/index')]));
          }
          else{
              exit(json_encode(['msg'=>'添加失败','status'=>0]));
          }
        }
    }

    function dele()
    {
        $id=input("get.id");
        $res=Db::name('category')->delete($id);
        if($res){
            addLog(2,'删除了单页面');
            exit('删除成功！');
        }
        else{
            exit("删除失败！");
        }
    }

    function edit($id)
    {
        if(!request()->isPost()){
            $sigel_page = Db::name("category")->where('modelid', "2")->select();
            $item=Db::name('category')->find($id);
            $this->assign('item',$item);
            $this->assign("sigel_page", $sigel_page);
            return $this->fetch();
        }
        else
        {
            $param=input("post.");
            unset($param['cid']);
            $res=Db::name('category')->where("id",$id)->update($param);
            if($res)
            {
                addLog(2,'修改了单页面');
                exit(json_encode(['msg'=>'修改成功！',"status"=>1,'url'=>url('page/index')]));
            }
            else
            {
                exit(json_encode(['msg'=>'修改失败！','status'=>0]));
            }
        }
    }
}