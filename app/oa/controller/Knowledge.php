<?php
/**
 * 知识控制类
 */

namespace app\oa\controller;

use \think\Request;

class Knowledge extends Base{
    /**空操作 */
    public function _empty()
    {
        return $this -> fetch('empty/error');
    }
    
    /**添加知识 */
    public function add(Request $request)
    {
        if($request -> isPost()){
            $post = $request -> post();
            $file = $request -> file('thumb');
            $result = Model('Knowledge') -> saveData($post, $file);
            if($result){
                $this -> success('添加成功！', 'knowledge/showList');
            }else{
                $this -> error('添加失败！');
            }
        }else{
            return $this -> fetch();
        }
    }

    /**显示知识列表 */
    public function showList()
    {
        $model = Model('Knowledge');
        $data = $model -> paginate(10);
        $count = $model -> count();
        $page = $data -> render();

        $this -> assign('data', $data);
        $this -> assign('count', $count);
        $this -> assign('page', $page);
        return $this -> fetch();
    }

    /**下载附件 */
    public function download()
    {
        $id = input('id');
        $data = Model('Knowledge') -> find($id);
        if($data){
            $filepath = ROOT_PATH . ltrim($data -> picture, DS);
            if(file_exists($filepath)){
                header("Content-type: application/octet-stream");
                header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
                header("Content-Length: ". filesize($filepath));
                readfile($filepath);
            }else{
                return $this -> error("文件不存在！");
            }
        }else{
            return $this -> error('文件不存在！');
        }
    }

    /**显示内容 */
    public function show()
    {
        $id = input('id');
        $data = Model('Knowledge') -> find($id);
        return $data['content'];
    }

    /**编辑知识 */
    public function edit(Request $request)
    {
        $model = Model('knowledge');
        if($request -> isPost()){
            $post = $request -> post();
            $file = $request -> file('thumb');
            $result = $model -> updateData($post, $file);
            if($result){
                $this -> success('编辑成功！', 'knowledge/showList');
            }else{
                $this -> error('编辑失败！');
            }
        }else{
            $id = $request -> get('id');
            $data = $model -> find($id);
            $this -> assign('data', $data);
            return $this -> fetch(); 
        }
    }

    /**删除知识 */
    public function del()
    {
        $id = input('id');
        $result = Model('Knowledge') -> where('id', 'in', $id) -> delete();
        if($result){
            $this -> success('删除成功！', 'knowledge/showList');
        }else{
            $this -> error('删除失败！');
        }
    }
}