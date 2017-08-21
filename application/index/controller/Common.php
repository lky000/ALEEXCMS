<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/16
 * Time: 19:59
 */
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Config;
use think\Hook;
class Common extends Controller{
    protected $prefix = '';

//初始化所需要的数据
    function __construct()
    {
        parent::__construct();
        $title=Db::name('system')->where('name','site_name')->find();
        $title=$title['value'];
        $nav=Db::name('category')->where("modelid", "in" ,['1','2'])->select();
        $comment=Db::name('comment')->alias('c')->join('mycms_article a','a.id=c.aid')->field('a.id,a.title as atitle,c.title as ctitle,c.username')->order('create_time DESC')->paginate(5);
        $this->assign('comment',$comment);
        $this->prefix = Config::get('database.prefix');
        $this->assign('nav',$nav);
        $this->assign('title',$title);
        $article=Db::name('article')->order('flag DESC,publishtime DESC')->paginate(10);
        $this->assign('article',$article);
        $this->assign('page',$article->render());

    }


    /*
     * 空操作
     */
    public function _empty()
    {
        abort(404,'页面不存在啊，别乱入啊！');
    }


}