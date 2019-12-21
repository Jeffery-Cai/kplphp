<?php
namespace app\common\model;
use think\Model;
class Csvceshi extends Model
{
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    protected $name = 'csvceshi';

    static public function getSelect($where=[],$field='*',$page='',$order='id desc')
    {
        return self::where($where)->field($field)->page($page)->order($order)->select();
    }

    static public function getInfo($where=[],$field='*')
    {
        return self::where($where)->field($field)->find();
    }
}