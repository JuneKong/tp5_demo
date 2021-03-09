<?php
// 声明命名空间
namespace app\oa\controller;
// 引入Controller父类文件
use \think\Controller;
use \think\captcha\Captcha;
use \think\Session;

/**公共的控制器 */
class Mutual extends Controller
{
    /**空操作 */
    public function _empty()
    {
        return $this -> fetch('empty/error');
    }
    /**登录 */
    public function login()
    {
        // 展示视图
        // 第一种：父类控制器中的fetch方法
        // return $this->fetch();
        // 第二种：系统提供的助手函数 view
        if(request() -> isPost()){
            $post = input('post.');
            // 验证验证码,验证码不对直接就抛出错误，因此返回值肯定是成功的
            $resl = $this->validate($post,[
                        'captcha|验证码'=>'require|captcha'
                    ]);
            if($resl === true){
                $model = Model('User');
                unset($post['captcha']);
                $data = $model -> where($post) -> find();
                if($data){
                    // 用户信息保存到Session
                    Session::set('id', $data['id']);
                    Session::set('username', $data['username']);
                    Session::set('role_id', $data['role_id']);
                    $this -> success('欢迎登录', url('/oa/index/index'));
                }else{
                    $this -> error('用户或密码不正确！');
                }
                // $res = get_url_data($post, 'post');
                // if($res['state'] == 1){
                //     $data = $res['data'];
                //     // 用户信息保存到Session
                //     Session::set('id', $data['id']);
                //     Session::set('username', $data['username']);
                //     Session::set('role_id', $data['role_id']);
                //     $this -> success('欢迎登录', url('/oa/index/index'));
                // }else{
                //     $this -> error($res['msg']);
                // }
            }else{
                $this -> error($resl);
            }
        }else{
            return view();
        }
    }

    /**退出 */
    public function logout()
    {
        session(null);
        $this -> success('退出成功！', url('/oa/mutual/login'));
    }

    /**获取验证码 */
    public function captcha()
    {
        $conf = [
            'fontSize'      => 22,
            'useCurve'      => false,
            'useNoise'      => false,
            'length'        => 4,
        ];
        $captcha = new Captcha($conf);
        return $captcha->entry();
    }
}