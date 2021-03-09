<?php
/**
 * 部门控制类
 */
namespace app\oa\controller;

class Dept extends Base{
    /**空操作 */
    public function _empty()
    {
        return $this -> fetch('empty/error');
    }
    
    /**部门列表 */
    public function showList()
    {
        $model = Model('Dept');
        $data = $model -> order('sort asd') -> select();
        // 二次查询获得上级部门名称
        foreach ($data as $key => $value) {
            if($value['pid'] !== '0'){
                $pidname = $model -> find($value['pid']);
                if($pidname){
                    $data[$key]['pidName'] = $pidname['name'];
                }
            }
        }
        import('@.tree');
        $data = getTree($data);
        $this -> assign("data", $data);
        return $this -> fetch();
    }

    /**添加部门 */
    public function add()
    {
        if(request()->isPost()){
            $model = Model('Dept');
            $data = input('post.');
            $res = $model -> create($data);
            if($res){
                $this -> success('添加成功', 'dept/showList');
            }else{
                $this -> error('添加失败');
            }
        }else {
            $model = new \app\oa\model\Dept;
            $data = $model -> where('pid', 0) -> select();
            $this -> assign('data', $data);
            return $this -> fetch();
        }
    }

    /**修改部门信息 */
    public function edit()
    {
        if(request() -> isPost()){
            $post = input('post.');
            $model = Model('Dept');
            $res = $model -> save($post, $post['id']);
            if($res !== false){
                $this -> success('编辑成功！', url('/oa/dept/showList'));
            }else{
                $this -> error('编辑失败。');
            }
        }else{
            //注：pathinfo地址参数不能通过get方法获取，查看“获取PARAM变量”
            // input()默认获得param类型的参数
            $get = input('');
            $model = Model('Dept');
            $data = $model -> find($get['id']);
            $pids = $model -> where('id', 'not in', $get['id']) -> select(); 
            $this -> assign('data', $data);
            $this -> assign('pids', $pids);
            return $this -> fetch();
        }
    }

    /**删除部门 */
    public function del()
    {
        $get = input('');
        $model = Model('Dept');
        $res = $model -> where('id', 'in', $get['id']) -> delete();
        if($res){
            $this -> success('删除成功！', url('/oa/dept/showList'));
        }else{
            $this -> error('删除失败');
        }
    }
}
