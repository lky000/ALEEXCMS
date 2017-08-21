<?php

namespace app\admin\controller;

use think\Config;
use think\Controller;
use think\Db;
use think\Exception;
use think\Request;
use think\Image;
use think\Cache;
use think\Session;

class Index extends Common
{
    public function index()
    {
        $userid = Session::get('userinfo.id');
        $this->assign('userid', $userid);
        return $this->fetch('main');
    }

    public function manageCenter()
    {
        //环境
        $mysql_version = Db::query('SELECT VERSION() as version');
        $soft_env = input('server.SERVER_SOFTWARE');
        $php = phpversion();
        $software['php'] = $php;
        $software['os'] = PHP_OS;
        $software['env'] = $soft_env;
        $software['mysql'] = $mysql_version[0]['version'];
        $software['gd'] = extension_loaded('gd') ? '是' : '否';
        $this->assign('software', $software);
        //获取管理员登录信息
        $uid = Session::get('userinfo.id');
        $login_list = Db::name('log')->where(['userid' => $uid, 'type' => 1])->order('datetime DESC')->limit(5)->select();
        $this->assign('login_list', $login_list);
        //统计产品、文章总数量
        $product_count = Db::name('product')->where('status', 0)->count();
        $article_count = Db::name('article')->where('status', 0)->count();
        //网站主题
        $pc_theme = get_system_value('site_theme');
        $mobile_theme = get_system_value('site_mobile_theme');
        $this->assign(['pc_theme' => $pc_theme, 'mobile_theme' => $mobile_theme]);
        $this->assign('product_count', $product_count);
        $this->assign('article_count', $article_count);
        //获取单页栏目
        $single_page = Db::name('category')->field('id,name,ename')->where('modelid', 2)->select();
        //添加日志信息
        addLog(2,'查看管理中心');
        return $this->fetch('index\index');
    }

    function system()
    {
        if (!request()->isAjax()) {
            //获取系统设置项
            $list = Db::name('system')->select();/*
            if(Session::get('userinfo.user_type')<2)
            {return $this->fetch('\noAccess');}*/
            $this->assign('list', $list);

            return $this->fetch();
        } else {
            //插入、更新操作
            try {
                $params = input('post.');
                foreach ($params as $name => $value) {
                    $flag = Db::name('system')->where('name', $name)->update(['value' => $value]);
                }
            } catch (Exception $e) {
                exit(json_encode(['status' => 0, 'msg' => '更新操作异常，请稍后重试', 'url' => '']));
            }
            //写入日志
            addLog('2','更新系统设置');
            exit(json_encode(['status' => 1, 'msg' => '更新成功', 'url' => '']));
        }

    }

    public function upload(Request $req)
    {
        if (!input('?param.act')) {
            $file = $req->file("pic_url");
          $results= $this->validate(["file"=>$file],["file"=>'require|image'],['file.require'=>'请选择上传文件','file.image'=>'非法图像文件']);
          if(!$results)
          {
              exit(json_encode(['status' => 0, 'msg' =>'非法操作']));
          }
          $info = $file->rule('date')->move(ROOT_PATH.'public'.DS.'uploads');
            if ($info) {

                // 成功上传后 获取上传信息
                $path = $info->getRealPath();
                $filename = $info->getFilename();

                $save_name = $info->getSaveName();
//                $path=__UPLOADS__.DS.$save_name;
                /*if(__ROOT__){
                    $realpath =  __ROOT__.'/uploads/' . $save_name;
                }else{
                    $realpath =  '/uploads/' . $save_name;
                }*/
                if(__UPLOADS__){
                    $realpath =  __UPLOADS__.DS . $save_name;
                }else{
                    $realpath =  '/' . $save_name;
                }

                exit(json_encode(['status' => 1, 'path' => $realpath, 'save_name' => $save_name]));

            }
        } else {
            //删除图片
            $img_dir = input('param.path');
            $real_path = str_replace(__ROOT__, '', $img_dir);
            $path = str_replace(['/..\/', '/../'], '/', ROOT_PATH . $real_path);
            if (@unlink($path)) {

                exit(json_encode(['status' => 1, 'msg' => '删除成功']));
            } else {
                exit(json_encode(['status' => 0, 'msg' => '删除失败']));
            }
        }


    }
}
