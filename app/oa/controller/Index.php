<?php
/**
 * 后台页面控制器
 */
namespace app\oa\controller;

class Index extends Base{
    /**空操作 */
    public function _empty()
    {
        return $this -> fetch('empty/error');
    }

    public function index()
    {
        $data = Model('Email') -> where('to_id=1 and isread=0') -> count();
        // $data = Model('Email') -> where('to_id='.session('id').' and isread=0') -> count();
        $this -> assign('data', $data);
        return $this -> fetch();
    }

    public function home()
    {
        return $this -> fetch();
    }

    public function test()
    {
        $captcha = new \think\captcha\Captcha();
        return $captcha->entry(1);
    }

    /**获取ip地址 */
    public function test1()
    {
        echo request() -> ip();
    }

    /**获取ip地址的物理位置 */
    public function test2()
    {
        $get = input('');
        $ip = $get['ip'];
        // $ip = "88.88.88.88";// 默认设置一个ip地址(可自行获取)
        include VENDOR_PATH ."topthink/Iplocation.php";//引入文件
        $Ips =new \IpLocation\IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
        $area = $Ips->getlocation($ip); // 获取某个IP地址所在的位置
        dump($area);die;
    }

    public function test3()
    {
        // print_r(session_save_path());
        // dump(\think\Config::get('roles_auth'));
        // dump(request()->controller());
        // $res = http_curl('http://api.com/oa/dept/edit', array('id'=> 11));
        // return $res;

        // $res = get_data(array('id'=> 11, 'm'=> 'oa', 'c'=>'dept', 'a'=>'edit'));
        // return $res;
        $res = file_get_contents('http://api.com/oa/dept/edit/id/11');
        dump($res);die;
    }
}