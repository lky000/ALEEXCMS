<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/11
 * Time: 8:52
 */
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Session;
class Banner extends Common {
    function index($type='1'){
        $id=1;
       $bannerInfo=Db::name('banner')->select();
        $this->assign('type',$type);//banner类型
       $this->assign("bannerInfo",$bannerInfo);
     return $this->fetch();
    }
    function add(){
      if(!request()->isPost())
         {return $this->fetch();}
      else{
          $data['type']=input('post.type');
          if($data['type']==1)
          { $data['title']=input('post.title');}
          else
              {
                  $data['title']=input('post.title');
                  $data['start_time']=input('post.start_time');
                  $data['end_time']=input('post.end_time');
          }
          $res=Db::name('banner')->insert($data);
          if($res)
          {
              addLog(2,'添加了banner');
           exit(json_encode(['msg'=>'添加成功！','status'=>1,'url'=>url('banner/index')]));
          }
          else
          {exit(json_encode(['msg'=>'添加失败！','status'=>0]));}
           }
    }
    function edit($id){
       if(!request()->isPost())
       {
           $info=Db::name('banner')->find($id);
           $this->assign('info',$info);
           return $this->fetch();
       }
       else
       {
           $res=Db::name('banner')->where("id",$id)->update(input('post.'));
           if($res){
               addLog(2,'修改了banner信息');
               exit(json_encode(['msg'=>'修改成功！','status'=>1,'url'=>url('banner/index')]));
           }
           else
           {exit(json_encode(['msg'=>'修改失败！','status'=>0]));
           }
       }
    }
    function banlist($id){
        $banner=Db::name('banner')->find($id);
        $list=Db::name('banner_detail')->where("pid",$id)->select();
        $this->assign('list',$list);
        $this->assign('banner',$banner);
        return $this->fetch();
    }
    function addDetail($id){
        if(!request()->isPost())
        {
            $this->assign('pid',$id);
            return $this->fetch();
        }
        else
        {
$data['pid']=$id;
$data['title']=input('post.title');
$data['url']=input('post.url');
$data['img']=input('post.img-url');
$res=Db::name('banner_detail')->insert($data);
if($res){
    addLog(2,'添加了banner详细信息');
    exit(json_encode(['msg'=>'添加成功',"status"=>1,"url"=>url('banner/banlist',["id"=>$id])]));
}
else{
    exit(json_encode(['msg'=>'添加失败',"status"=>0]));
}
        }
    }
    function deleDetail(){
        $id=input('get.id');
        $res=Db::name('banner_detail')->delete($id);
        if($res)
        {  addLog(2,'删除了banner详细信息');
            exit('删除成功！');
        }
        else
        {
            exit('删除失败！');
        }
    }
    function editDetail($id){
        if(!request()->isPost()){
           $item=Db::name('banner_detail')->find($id);
           $this->assign('item',$item);
            return $this->fetch();
        }
        else{

            $data['title']=input("post.title");
            $data['url']=input('post.url');

            $data['img']=input('post.pic_url');
            $res=Db::name('banner_detail')->where("id",$id)->update($data);
            if($res){
                addLog(2,'修改了banner详细信息');
                exit(json_encode(['msg'=>'修改成功',"status"=>1,"url"=>url('banner/banlist',['id'=>input('post.pid')])]));
            }
            else{
                exit(json_encode(['msg'=>"修改失败","status"=>0]));
            }
        }
    }
    function dele(){
  $id=input('get.id');
  $res=Db::name('banner')->delete($id);
  if($res)
  {
      addLog(2,'删除了banner');
      exit('删除成功！');
  }
  else
  {
      exit('删除失败！');
  }
    }
}