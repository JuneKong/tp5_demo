<?php
/**
 * 用户模型类
 */
namespace app\oa\model;

class User extends Base{
    protected $table = 'sp_user';

    public function dept()
    {
        return $this -> hasOne('Dept', 'id') -> field('id,name');
    }
}