<?php
/**
 * 用户模型类
 */
namespace app\oa\model;

class Document extends Base{
    protected $table = 'sp_document';

    /**保存公文数据 */
    public function saveData($post, $file)
    {
        if($post){
            if($file){
                $info = $file -> move( ROOT_PATH . FILE_UPLOADS_PATH);
                if($info){
                    $post['filepath'] = DS . FILE_UPLOADS_PATH .  $info -> getSaveName();
                    $post['filename'] = $info -> getInfo('name');
                    $post['hasfile'] = 1;
                }else{
                    return false;
                }
            }
            $result = $this -> create($post);
            return $result;
        }else{
            return false;
        }
    }

    /**更新公文数据 */
    public function updateData($post, $file)
    {
        if($file){//处理文件
            $info = $file -> move( ROOT_PATH . FILE_UPLOADS_PATH);
            if($info){
                $post['filepath'] = DS . FILE_UPLOADS_PATH . $info -> getSaveName();
                $post['filename'] = $info -> getInfo('name');
                $post['hasfile'] = 1;
            }
        }
        return $this -> save($post, $post['id']);
    }
}