<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/5
 * Time: 11:34
 */
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Config;
use think\Hook;

class Common extends Controller{
    protected $prefix = '';
    protected $all_nav;
    protected $sec_nav;
//初始化所需要的数据
    function __construct()
    {
        parent::__construct();
        $this->all_nav=getMenu();
        $this->sec_nav=getSecondMenu();
        $this->prefix = Config::get('database.prefix');
        $this->checkLogin();
        $this->assign('web_site',$this->request->domain());
        $this->assign('all_nav', $this->all_nav);//获取所有导航
        $this->assign("sec_nav",$this->sec_nav);//获取子导航

    }
    /**
     * 验证是否登录
     */
    protected function checkLogin(){

        //验证是否登录成功
        if (!Session::has('userinfo') || !$uname = Session::get('userinfo.name')) {
            $this->redirect('login/index');
        }
        //登录是否过期 无操作1h即为过期
        $login_time = Session::get('userinfo.login_time');
        if (time() - $login_time > 3600) {
            Session::clear();
            $this->redirect('login/index');
        }
        Session::set('userinfo.login_time',time());
        $this->assign('username', $uname);
    }

    /**
     * 无刷新重载栏目
     * @return json
     */
    function reloadCategory(){
        $cate = getMenu();
        exit(json_encode($cate));
    }
    /*
     * 空操作
     */
    public function _empty()
    {
        abort(404,'页面不存在啊，别乱入啊！');
    }


}