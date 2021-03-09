<?php
/**
 * 职员控制类
 */
namespace app\oa\controller;

class User extends Base{
    /**空操作 */
    public function _empty()
    {
        return $this -> fetch('empty/error');
    }
    
    /**职员列表 */
    public function showList()
    {
        // $res = get_data(array('m' => 'oa', 'c' => 'user', 'a' => 'showList'));
        $get = input('page');
        // dump($get);die;
        $res = get_url_data($get == null ? array(): $get);
        // $model = Model('User');
        // $data = $model -> paginate(3);
        // $count = $model -> count();
        // $page = $data -> render();
        if($res['state'] == 1){
            $info = $res['data'];
            $this -> assign("data", $info['data']['data']);
            $this -> assign("page", $info['page']);
            $this -> assign("count", $info['count']);
            return $this -> fetch();
        }
    }

    /**添加职员 */
    public function add()
    {
        if(request()->isPost()){
            // $model = Model('User');
            $data = input('post.');
            // $res = $model -> create($data);
            $res = get_url_data($data,'post');
            if($res){
                $this -> success('添加成功', 'user/showList');
            }else{
                $this -> error('添加失败');
            }
        }else {
            // $res = get_data(array('m' => 'oa', 'c' => 'user', 'a' => 'add'));
            $res = get_url_data();
            // dump($res);die;
            // $model = Model('Dept');
            // $dept = $model -> field('id, name') -> select();
            if($res['state'] == 1){
                $this -> assign('dept', $res['data']);
                return $this -> fetch();
            }
        }
    }

    /**修改职员信息 */
    public function edit()
    {
        if(request() -> isPost()){
            $post = input('post.');
            $model = Model('User');
            $res = $model -> save($post, $post['id']);
            if($res !== false){
                $this -> success('编辑成功！', url('/oa/user/showList'));
            }else{
                $this -> error('编辑失败。');
            }
        }else{
            //注：pathinfo地址参数不能通过get方法获取，查看“获取PARAM变量”
            // input()默认获得param类型的参数
            $get = input('');
            $model = Model('User');
            $data = $model ->alias('a') -> join('Dept d','a.dept_id = d.id') -> find($get['id']);
            $dept = $model -> dept() -> select();
            $this -> assign('data', $data);
            $this -> assign('dept', $dept);
            return $this -> fetch();
        }
    }

    /**删除职员 */
    public function del()
    {
        $get = input('');
        $model = Model('User');
        $res = $model -> where('id', 'in', $get['id']) -> delete();
        if($res){
            $this -> success('删除成功！', url('/oa/user/showList'));
        }else{
            $this -> error('删除失败');
        }
    }

    /**职员统计 */
    public function charts()
    {
        $model = Model('User');
        $data = $model -> alias('u') -> field('name, count(*) as count') -> join('Dept d', 'u.dept_id = d.id') -> group('dept_id') -> select();
        $str = '[';
        foreach ($data as $key => $value) {
            $str .= "['" . $value['name']. "', ". $value['count'] ."],"; 
        }
        $str = rtrim($str, ',');
        $str .= ']';
        $this -> assign('data', $str);
        return $this -> fetch();
    }
}
