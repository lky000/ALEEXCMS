<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/13
 * Time: 12:07
 */

namespace app\admin\controller;

use think\Config;
use think\Controller;
use think\Db;
use think\Request;

class Article extends Common
{


    /*
     * 数据库配置参数
     */
    protected $db_config = [];


    function __construct()
    {
        parent::__construct();
        $this->db_config = Config::load(APP_PATH . '/database.php');
        $type = Db::name('category')->where('modelid', '1')->select();

        $this->assign('type', $type);
    }

    function index()
    {
        $id = input('?get.id') ? input('get.id') : 0;

        $article = Db::name('article')
            ->alias('a')
            ->join('mycms_category c', 'c.id=a.cid')
            ->field('a.id,a.title,a.publishtime,a.cid,a.click,a.flag,c.name')
            ->where('a.status', 0)
            ->order('a.flag DESC,a.publishtime DESC');
        if ($id == 0) {
            $article = $article->paginate(10);
        } else {
            $article = $article->where('cid', $id)->paginate(10);
        }
        $page = $article->render();
        if ($article->total() < 1) {
            $this->assign('empty', "<tr><td colspan='7'>暂无数据</td></tr>");
        }

        $this->assign('article', $article);
        $this->assign('id', $id);
        $this->assign('page', $page);

        return $this->fetch();
    }

    function add()
    {
        if (!request()->isPost()) {
            return $this->fetch();
        } else {
            $data['title']=input('post.title');
            $data['cid']=input('post.cid');
            $data['description']=input('post.description');
            $data['content']=input('post.content');

            $data['litpic']=input('?post.img-url')?input('post.img-url'):'';
            $data['keywords']=input('post.keywords');
            $data['publishtime']=strtotime('now');
            $res = DB::name('article')->insert($data);
            if ($res) {
                addLog(2,'添加了文章');
             exit(json_encode(['msg'=>'添加成功！','url'=>url('article/index'),'status'=>1]));
            } else {
exit(json_encode(['msg'=>'添加失败!','status'=>0]));
            }
        }
    }

    function edit($id)
    {
        if(!request()->isPost()){
            $item=Db::name('article')->find($id);
            $this->assign('item',$item);
            return $this->fetch();

        }
        else{
            $data['title']=input('post.title');
            $data['cid']=input('post.cid');
            $data['description']=input('post.description');
            $data['content']=input('post.content');

                $data['litpic'] =   input('?post.pic_url')?input('post.pic_url'):"";
            $data['keywords']=input('post.keywords');
            $data['publishtime']=strtotime('now');

            $res = DB::name('article')->where('id',$id)->update($data);
            if ($res) {
                addLog(2,'编辑了文章信息');
                exit(json_encode(['msg'=>'编辑成功！','url'=>url('article/index'),'status'=>1]));
            } else {
                exit(json_encode(['msg'=>'编辑失败!','status'=>0]));
            }
        }
    }

    function dele()
    {
        if(input('?get.id'))
        { $id = input('get.id');
        $res = Db::name('article')->delete($id);}
        else
        {
            $ids=input('get.checkbox/a');
            $res=Db::name('article')->where('id','in',$ids)->delete();
        }
        if ($res) {
            addLog(2,'删除了文章信息');
            exit('删除成功！');
        } else {
            exit('删除失败！');
        }
    }

    function move()
    {

            $ids=input('get.checkbox/a');
            $cid=input('get.new_cat_id');
            $res=Db::name('article')->where('id','in',$ids)->update(['cid'=>$cid]);
        if ($res) {
            exit('操作成功！');
        } else {
            exit('操作失败！');
        }
    }
    function topit(){
        $id=input('post.id');
        $flag=input('post.flag');
        $flag=$flag?0:1;
        $res=Db::name('article')->where('id',$id)->update(['flag'=>$flag]);
        if($res)
        {
            exit(json_encode(['msg'=>'操作成功！','status'=>1,]));
        }
        else
        {  exit(json_encode(['msg'=>'操作失败！','status'=>1,]));}
    }
    function copy(){
        if(!request()->isPost())
        {
            return $this->fetch();
        }
    }
}