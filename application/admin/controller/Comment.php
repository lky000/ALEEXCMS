<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/13
 * Time: 10:49
 */
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Session;
class Comment extends Common{
    function index(){
        $list=Db::name('comment')->paginate(20);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        return $this->fetch();
    }
    function add($id){
        if(!request()->isPost())
        {
            $item=Db::name('comment')->find($id);
            $this->assign('item',$item);
            return $this->fetch();
        }
        else{
            $userid=Session::get('userinfo.id');
            $email=Db::name('admin')->where('id',$userid)->value('email');
            $data['email']=$email;

            $data['title']=input('post.title');
            $data['username']=input('post.username');
            $data['rid']=input('post.rid');
            $data['content']=input('post.content');
            $res=Db::name('comment')->insert($data);

            if($res){
                addLog(2,'添加了评论');
                exit(json_encode(['msg'=>'添加成功!','status'=>1,'url'=>url('comment/index')]));
            }
            else
            {
                exit(json_encode(['msg'=>'添加失败！','status'=>0]));
            }
        }
    }
    function edit($id){
        if(!request()->isPost()){
            $item=Db::name('comment')->find($id);
            $this->assign('item',$item);
            return $this->fetch();
        }else{

            $data['email']=input('post.email');
            $data['title']=input('post.title');
            $data['username']=input('post.username');
            $data['rid']=input('post.rid');
            $data['content']=input('post.content');
            $res=Db::name('comment')->where('id',$id)->update($data);

            if($res){
                addLog(2,'修改了评论');
                exit(json_encode(['msg'=>'修改成功!','status'=>1,'url'=>url('comment/index')]));
            }
            else
            {
                exit(json_encode(['msg'=>'修改失败！','status'=>0]));
            }
        }
    }
    function dele(){
        $id=input('get.id');
        $res=Db::name('comment')->delete($id);
        if($res){
            addLog(2,'删除了评论');
            exit('删除成功');
        }
        else
        {exit('删除失败！');}
    }



}