<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8
 * Time: 22:30
 */
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Session;
 class Log extends Common {
     function index(){
      $list=Db::name('log')->where('userid',Session::get('userinfo.id'))->order('datetime DESC')->paginate(20);
      $this->assign("page",$list->render());
      $this->assign("list",$list);
    return  $this->fetch();
     }
 }