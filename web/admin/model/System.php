<?php
namespace app\admin\model;
use think\Model;
use think\Exception;
use util\Tree;
class System extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $name = 'admin_system';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

}