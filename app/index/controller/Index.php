<?php
namespace app\index\controller;
use think\facade\View;
class Index
{
    public function index()
    {

        return View::fetch();
    }

    public function sss()
    {
        halt(123);
    }
}