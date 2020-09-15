<?php
namespace app\common\controller;

/*
 * Kbuilder快速构建器
 */
class Kbuilder {

    protected static $vars = [];
    public function initialize()
    {

    }

    /**
     * kplphp构建器
     * @param string $t == table 表格构建器 ，== form 表单构建器
     * @return mixed
     */
    public static function sets($t='table')
    {
        if($t == 'table')
        {
            $class = new \app\common\kbuilder\table\Kbuilder();
        }elseif($t == 'see')
        {
            $class = new \app\common\kbuilder\see\Kbuilder();
        }elseif($t == 'form'){
            $class = new \app\common\kbuilder\form\Kbuilder();
        }else{
            # 可扩展xxx
        }
        return $class;
    }


}