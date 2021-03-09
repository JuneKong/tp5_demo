<?php
/**
 * 空控制类
 */

namespace app\oa\controller;

use \think\Controller;
class Error extends Controller
{
    public function index()
    {
        return $this -> fetch('empty/error');
    }
}