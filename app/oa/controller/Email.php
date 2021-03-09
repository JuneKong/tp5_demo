<?php
/**
 * 邮件控制类
 */

namespace app\oa\controller;

use \think\Request;

class Email extends Base{
    /**空操作 */
    public function _empty()
    {
        return $this -> fetch('empty/error');
    }
    
    /**发邮件 */
    public function send(Request $request)
    {
        if($request -> isPost()){
            $post = $request -> post();
            $file = $request -> file('file');
            $result = Model('Email') -> saveData($post, $file);
            if($result){
                $this -> success('发送成功！', 'email/sendBox');
            }else{
                $this -> error('发送失败！');
            }
        }else{
            $data = Model('User') -> where('id', 'not in', session('id')) -> select();
            $this -> assign('data', $data);
            return $this -> fetch();
        }
    }

    /**发件箱 */
    public function sendBox()
    {
        $data = Model('email') 
                -> alias('e') 
                -> join('User u', 'e.to_id = u.id')
                -> field('e.*,u.truename as truename') 
                -> where('from_id', session('id')) 
                -> paginate(10);
        $page = $data -> render();
        $count = $data -> count();
        $this -> assign('page', $page);
        $this -> assign('data', $data);
        $this -> assign('count', $count);
        return $this -> fetch();
    }

    /**下载附件 */
    public function download()
    {
        $id = input('id');
        $data = Model('Email') -> find($id);
        if($data){
            $filepath = ROOT_PATH . ltrim($data -> file, DS);
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

    /**收件箱 */
    public function recBox()
    {
        // select e.*, u.truename from sp_email as e inner join sp_user on e.from_id = u.id where e.to_id = 1;
        $data = Model('Email') 
                -> alias('e')
                -> field('e.*, u.truename as truename')
                -> join('User u', 'e.from_id = u.id')
                -> where('to_id', session('id')) 
                -> paginate(10);
        $page = $data -> render();
        $count = $data -> count();
        $this -> assign('data', $data);
        $this -> assign('page', $page);
        $this -> assign('count', $count);
        return $this -> fetch();
    }

    /**查看邮件 */
    public function show()
    {
        $id = input('id');
        $model = Model('Email');
        $data = $model -> where('id='.$id.' and to_id='.session('id')) -> find();
        if($data['isread'] == 0){
            $model -> where('id', $id) -> update(['isread' => '1']);
        }
        return $data['content'];
    }

    /**获得收件箱数量 */
    public function getCount()
    {
        $data = Model('Email') -> where('isread=0 and to_id='.session('id')) -> count();
        return $data;
    }
}
