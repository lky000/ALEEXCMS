<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/15
 * Time: 17:46
 */
namespace app\admin\controller;
use think\Config;
use think\Controller;
use think\Db;
use think\Request;

class Product extends Common{
    protected $db_config = [];


    function __construct()
    {
        parent::__construct();
        $this->db_config = Config::load(APP_PATH . '/database.php');
        $type = Db::name('category')->where('modelid', '3')->select();
        $this->assign('type', $type);
    }

    function index(){
        $id = input('?get.id') ? input('get.id') : 0;

        $product = Db::name('product')
            ->alias('a')
            ->join('mycms_category c', 'c.id=a.cid')
            ->field('a.id,a.title,a.publishtime,a.cid,a.click,a.flag,c.name')
            ->where('a.status', 0)
            ->order('a.flag DESC,a.publishtime DESC');
        if ($id == 0) {
            $product = $product->paginate(10);
        } else {
            $product = $product->where('cid', $id)->paginate(10);
        }
        $page = $product->render();
        if ($product->total() < 1) {
            $this->assign('empty', "<tr><td colspan='7'>暂无数据</td></tr>");
        }

        $this->assign('product', $product);
        $this->assign('id', $id);
        $this->assign('page', $page);
        return $this->fetch();
    }
    function add(){
        if (!request()->isPost()) {
            return $this->fetch();
        } else {
            $data['title']=input('post.title');
            $data['cid']=input('post.cid');
            $data['description']=input('post.description');
            $data['content']=input('post.content');
            $data['litpic']=input('?post.img-url')?input('post.img-url'):'';
            $data['keywords']=input('post.keywords');
            $data['publishtime']=time();
            $data['units']=input('post.units');
            $data['brand']=input('post.brand');
            $data['price']=input('post.price');
            $res = DB::name('product')->insert($data);
            if ($res) {
                addLog(2,'添加了产品模型');
                exit(json_encode(['msg'=>'添加成功！','url'=>url('product/index'),'status'=>1]));
            } else {
                exit(json_encode(['msg'=>'添加失败!','status'=>0]));
            }
        }

    }
    function edit($id){
        if(!request()->isPost()){
            $item=Db::name('product')->find($id);
            $item['pic_url'] = explode('|',$item['pictureurls']);
            $this->assign('item',$item);
            return $this->fetch();

        }
        else{
            $data['title']=input('post.title');
            $data['cid']=input('post.cid');
            $data['description']=input('post.description');
            $data['content']=input('post.content');
            if(input('?post.pic_url')){
//                $params = input('post.');
                $data['pictureurls'] = input('post.pic_url');
//                $data['litpic'] =input('post.pic_url');
            }
            else{
                $data['pictureurls'] = '';
//                $data['litpic'] ='';
            }

            $data['keywords']=input('post.keywords');
            $data['publishtime']=time();
            $data['units']=input('post.units');
            $data['brand']=input('post.brand');
            $data['price']=input('post.price');

            $res = DB::name('product')->where('id',$id)->update($data);
            if ($res) {
                addLog(2,'修改了产品模型');
                exit(json_encode(['msg'=>'编辑成功！','url'=>url('product/index'),'status'=>1]));
            } else {
                exit(json_encode(['msg'=>'编辑失败!','status'=>0]));
            }
        }
    }
    function dele(){
        if(input('?get.id'))
        { $id = input('get.id');
            $res = Db::name('product')->delete($id);}
        else
        {
            $ids=input('get.checkbox/a');
            $res=Db::name('product')->where('id','in',$ids)->delete();
        }
        if ($res) {
            addLog(2,'删除了产品模型');
            exit('删除成功！');
        } else {
            exit('删除失败！');
        }
    }
    function move(){
        $ids=input('get.checkbox/a');
        $cid=input('get.new_cat_id');
        $res=Db::name('product')->where('id','in',$ids)->update(['cid'=>$cid]);
        if ($res) {
            addLog(2,'移动了产品模型');
            exit('操作成功！');
        } else {
            exit('操作失败！');
        }
    }
    function topit(){
        $id=input('post.id');
        $flag=input('post.flag');
        $flag=$flag?0:1;
        $pname=Db::name('product')->find($id);
        $res=Db::name('product')->where('id',$id)->update(['flag'=>$flag]);
        if($res)
        {
            addLog(2,"将{$pname['title']}置顶");
            exit(json_encode(['msg'=>'操作成功！','status'=>1,]));
        }
        else
        {  exit(json_encode(['msg'=>'操作失败！','status'=>1,]));}
    }
}