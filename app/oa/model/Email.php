<?php
/**
 * 邮件模型类
 */

namespace app\oa\model;

class Email extends Base{
    protected $tabel = 'sp_email';

    /**保存发件数据 */
    public function saveData($post, $file)
    {
        if($file){
            $info = $file -> move(ROOT_PATH . FILE_UPLOADS_PATH);
            if($info){
                $post['file'] = DS . FILE_UPLOADS_PATH . $info -> getSaveName();
                $post['hasfile'] = 1;
                $post['filename'] = $info -> getInfo('name');
            }else{
                return false;
            }
        }
        $post['from_id'] = session('id');
        return $this -> create($post);
    }
}