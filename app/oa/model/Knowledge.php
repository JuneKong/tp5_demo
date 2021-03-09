<?php
/**
 * 知识模块类
 */

namespace app\oa\model;

class Knowledge extends Base{
    protected $table = 'sp_knowledge';

    /**保存知识 */
    public function saveData($post, $file)
    {
        if($file){
            $info = $file -> move(ROOT_PATH . FILE_UPLOADS_PATH);
            if($info){
                $savename = $info -> getSaveName();
                //用于下载的文件，是以项目根目录开始的路径
                $post['picture'] = DS . FILE_UPLOADS_PATH . $savename;
                //生成缩略图
                $timeFile = explode(DS, $savename);
                $image = \think\Image::open($file);
                $image->thumb(150, 150)->save(ROOT_PATH . FILE_UPLOADS_PATH . $timeFile[0]. DS . 'thumb_' .$timeFile[1]);
                //显示在页面的静态图片缩略图,是以public为根目录
                $post['thumb'] = DS.'uploads' . DS . $timeFile[0]. DS . 'thumb_' .$timeFile[1];
            }else{
                return false;
            }
        }
        return $this -> create($post);
    }

    /**更新知识 */
    public function updateData($post, $file)
    {
        if($file){
            $info = $file -> move( ROOT_PATH . FILE_UPLOADS_PATH );
            if($info){
                $savename = $info -> getSaveName();
                $post['picture'] = DS . FILE_UPLOADS_PATH . $savename;
                $path = explode(DS, $savename);
                $image = \think\Image::open($file);
                $image -> thumb(150,150) -> save(ROOT_PATH . FILE_UPLOADS_PATH . $path[0] . "thumb_" . $path[1]);
                $post['thumb'] = DS . 'uploads' . DS . $path[0] . 'thumb_' . $path[1];
            }else{
                return false;
            }
        }
        return $this -> save($post, $post['id']);
    }
}