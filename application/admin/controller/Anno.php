<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/13
 * Time: 9:45
 */
namespace app\admin\controller;
use think\Controller;
use think\Db;
class Anno extends Common {
    function index(){

        $list=Db::name('flink')->where(['status'=>0,'type'=>2])->paginate(20);
        $this->assign('page',$list->render());
        $this->assign("list",$list);

        return $this->fetch();
    }
    function add()
    {
        if(!request()->isPost()){
         return $this->fetch();
        }
        else
        {
            $data['title']=input('post.title');
            $data['type']=input('post.type');
            $data['logo']=input('post.img-url');
            $data['description']=input('description');
            $res=Db::name('flink')->insert($data);
            if($res){
                addLog(2,'添加了公告信息');
                exit(json_encode(['msg'=>'添加成功!','status'=>1,'url'=>url('anno/index')]));
            }
            else
            {
                exit(json_encode(['msg'=>'添加失败！','status'=>0]));
            }
        }
    }
    function edit($id)
    {
        if(!request()->isPost())
        {
            $item=Db::name('flink')->find($id);
            $this->assign('item',$item);
            return $this->fetch();
        }
        else
        {
            $data['title']=input('post.title');
            $data['type']=input('post.type');
            $data['logo']=input('post.pic_url');
            $data['description']=input('description');
            $res=Db::name('flink')->where('id',$id)->update($data);
            if($res){
                addLog(2,'修改了公告信息');
                exit(json_encode(['msg'=>'修改成功!','status'=>1,'url'=>url('anno/index')]));
            }
            else
            {
                exit(json_encode(['msg'=>'修改失败！','status'=>0]));
            }
        }
    }
    function dele(){
  $id=input('get.id');
  $res=Db::name('flink')->delete($id);
  if($res){
      addLog(2,'删除了公告信息');
      exit('删除成功');
  }
  else
  {exit('删除失败！');}
    }
}