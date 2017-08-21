<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\Db;
use think\Session;
//无限级分类
function getMenu($pid=0,$level=0){
    $menu=Db::name('menu')->select();
    //return $menu;
    $all_menu=array();
    foreach($menu as $rows){
        if($rows['parentId']==$pid){
        $rows['level']=$level+1;
        $all_menu[]=$rows;
        $all_menu=array_merge($all_menu,getMenu($rows['menuId'],$level+1));
        }
    }

return $all_menu;

}

function getSecondMenu($pid=0,$level=0,$pname=''){
    $menu=Db::name('category')->select();
    $all_menu=array();
    foreach($menu as $rows){
        if($rows['pid']==$pid){
            $rows['level']=$level+1;
            $rows['pname']=$pname;
            $all_menu[]=$rows;
            $all_menu=array_merge($all_menu,getSecondMenu($rows['id'],$level+1,$rows['name']));
        }
    }
    return $all_menu;
}

function get_system_value($name=''){
    $value = Db::name('system')->where('name',$name)->value('value');
    return $value;
}
function addLog($type,$str){
    $data['type'] = $type;
    $data['datetime'] = time();
    $data['ip'] = request()->ip();
    $data['content'] = Session::get('userinfo.name') . "$str";
    $data['username'] = Session::get('userinfo.name');
    $data['userid'] = Session::get('userinfo.id');
    Db::name('log')->where('id', "{$data['userid']}")->insert($data);
}
