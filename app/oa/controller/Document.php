<?php
/**
 * 公文控制类
 */
namespace app\oa\controller;

class Document extends Base{
    /**空操作 */
    public function _empty()
    {
        return $this -> fetch('empty/error');
    }
    
    /**添加公文 */
    public function add()
    {
        if(request() -> isPost()){//是否是post请求
            $post = input('post.');
            $model = Model('Document');
            $file = request() -> file('file');
            $result = $model -> saveData($post, $file);
            if($result){
                $this -> success('添加成功！', '/oa/document/showList');
            }else{
                $this -> error("添加失败: {$file -> getError()}");
            }
        }else{
            return $this -> fetch();
        }
    }

    /**显示列表 */
    public function showlist()
    {
        $model = Model('Document');
        $data = $model -> paginate(10);
        $count = $model -> count();
        $page = $data -> render();
        $this -> assign('data', $data);
        $this -> assign('count', $count);
        $this -> assign('page', $page);
        return $this -> fetch();
    }

    /**下载文件 注：不可封装成模型Model中*/
    public function download()
    {
        $get = input('');
        $model = Model('Document');
        $file = $model -> find($get['id']);
        if($file){
            $filepath = ROOT_PATH . ltrim($file -> filepath, DS);
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

    /**显示公文内容 */
    public function showContent()
    {
        $get = input('');
        $model = Model('Document');
        $data = $model -> find($get['id']);
        return $data['content'];
    }

    /**编辑公文 */
    public function edit()
    {
        if(request() -> isPost()){
            $post = input('post.');
            $model = Model('Document');
            $file = request() -> file('file');
            $result = $model -> updateData($post, $file);
            if($result){
                $this -> success('编辑成功！', 'document/showList');
            }else{
                $this -> error('编辑失败！');
            }
        }else{
            $get = input('');
            $data = Model('Document') -> find($get['id']);
            $this -> assign('data', $data);
            return $this -> fetch();
        }
    }

    /**删除公文 */
    public function del()
    {
        $id = input('id');
        $result = Model('Document') -> where('id', 'in', $id) -> delete();
        if($result){
            $this -> success('删除成功！', 'document/showList');
        }else{
            $this -> error('删除失败！');
        }
    }
}