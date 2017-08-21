<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/12
 * Time: 17:47
 */
namespace app\admin\controller;
use think\Db;
use think\Controller;
class Flink extends Common {
    function index(){

        $flink=Db::name('flink')->paginate(10);
        $this->assign("page",$flink->render());
        $this->assign('flink',$flink);
        return $this->fetch();
    }
    function add(){
        if(!request()->isPost())
        {
            return $this->fetch();
        }
        else{
            $data['title']=input('post.title');
            $data['type']=input('post.type');
            $data['logo']=input('post.img-url');
            $data['url']=input('post.url');
            $data['description']=input('description');
            $res=Db::name('flink')->insert($data);
            if($res){
                addLog(2,'添加了友情链接');
                exit(json_encode(['msg'=>'添加成功!','status'=>1,'url'=>url('flink/index')]));
            }
            else
            {
                exit(json_encode(['msg'=>'添加失败！','status'=>0]));
            }
        }

    }
    function edit($id){
if(!request()->isPost()){
    $item=Db::name('flink')->find($id);
    $this->assign('item',$item);
    return $this->fetch();
}else{
    $data['title']=input('post.title');
    $data['type']=input('post.type');
    $data['logo']=input('post.pic_url');
    $data['url']=input('post.url');
    $data['description']=input('description');
    $res=Db::name('flink')->where('id',$id)->update($data);
    if($res){
        addLog(2,'修改了友情链接');
        exit(json_encode(['msg'=>'修改成功!','status'=>1,'url'=>url('flink/index')]));
    }
    else
    {
        exit(json_encode(['msg'=>'修改失败！','status'=>0]));
    }
}
    }
    function dele(){
        $id=input('get.id');
        $res=Db::name('flink')->where('id',$id)->delete();
        if($res) {
            addLog(2,'删除了友情链接');
            echo '删除成功！';
        }
        else{
            echo '删除失败！';
        }
    }
}