<?php
/**
 * 基础控制类
 */

namespace app\oa\controller;

use \think\Controller;
use \think\Config;

class Base extends Controller{
    //创建构造函数有两个：
    // 1.php的构造函数__construct
    // public function __construct()
    // {
    //     parent::__construct();

    // }
    // 2.ThinkPHP封装的构造函数_initialize;
    /**
     * 初始化操作
     * @access protected
     */
    protected function _initialize()
    {
        // $id = session('id');
        // if(empty($id)){
        //     // $this -> error('您还未登录或登录过时！', 'mutual/login'); exit;
        //     // 上述跳转不能实现整个页面的跳转，只能在iframe里跳转，这样会形成无限嵌套的效果
        //     // 解决：使用JavaScript代码实现效果
        //     echo "<script type='text/javascript'>top.location.href = '/oa/mutual/login';</script>";
        //     exit;
        // }

        $role_id = session('role_id');
        if($role_id > 1){
            $conf = Config::get('role_auths_path');
            $currAuth = $conf[$role_id];
            $ctrl = strtolower(request()->controller());
            $action = strtolower(request()->action());
            if(!in_array($ctrl.$action, $currAuth) && !in_array($ctrl.'/*', $currAuth)){
                $this -> error('您没有当前页面权限！', 'index/home');exit;
            }
        }
    }
}