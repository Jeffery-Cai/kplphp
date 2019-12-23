<?php
/*----------------------------------------------------------------
 * 版权所有 2019~2020 极盾工作室  kplphp地址[ http://www.kplphp.com ]
 * 作者由JefferyCai码云所创造 [ https://gitee.com/JefferyCai ]
 * 当前码云地址 与 操作文档都在 [ https://gitee.com/JefferyCai/kplphp ]
 * QQ群请加 972703635 [ https://jq.qq.com/?_wv=1027&k=5YnmIH8 ]，如有更多服务，请单独加群主: 1345199080
 * 测试CSV导入功能
----------------------------------------------------------------*/
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