<?php
/*----------------------------------------------------------------
 * 版权所有 2019~2020 极盾工作室  kplphp地址[ http://www.kplphp.com ]
 * 作者由JefferyCai码云所创造 [ https://gitee.com/JefferyCai ]
 * 当前码云地址 与 操作文档都在 [ https://gitee.com/JefferyCai/kplphp ]
 * QQ群请加 972703635 [ https://jq.qq.com/?_wv=1027&k=5YnmIH8 ]，如有更多服务，请单独加群主: 1345199080
 * 上传功能
----------------------------------------------------------------*/
namespace app\admin\controller;

use think\facade\Db;

class Upload
{
    public function file()
    {
        $type = input('type');
        $file = request()->file('file');
        if(!$file)return;
        # 处理上传的文件
        $filev = [];
        $new = [];
        if(!is_array($file))
        {
            $filev[] = \think\facade\Filesystem::disk('public')->putFile( 'uploads', $file, 'md5');
        }else{
            foreach ($file as $k1 => $v1)
            {
                $filev[] = \think\facade\Filesystem::disk('public')->putFile( 'uploads', $v1, 'md5');
            }
        }
        foreach ($filev as $k => $v)
        {
            $iid = Db::name('file')->insertGetId(['type'=>$type,'filename'=>'/storage/'.$v]);
            $new[$iid]['filename'] = '/storage/'.$v;
        }
        return json(['status'=>1,'data'=>$new]);
    }

    public function delfile()
    {
        $f = Db::name('file')->where(['id'=>input('id')])->delete();
        if($f)
        {
            return json(['status'=>1]);
        }else{
            return json(['status'=>0]);
        }
    }
}