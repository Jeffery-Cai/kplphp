<?php
/*----------------------------------------------------------------
 * 版权所有 2019~2020 极盾工作室  kplphp地址[ http://www.kplphp.com ]
 * 作者由JefferyCai码云所创造 [ https://gitee.com/JefferyCai ]
 * 当前码云地址 与 操作文档都在 [ https://gitee.com/JefferyCai/kplphp ]
 * QQ群请加 972703635 [ https://jq.qq.com/?_wv=1027&k=5YnmIH8 ]，如有更多服务，请单独加群主: 1345199080
 * 测试功能
----------------------------------------------------------------*/
namespace app\admin\controller;
use app\AdminController;
use app\common\model\Csvceshi;
use think\App;
use util\PHPCsv;

class Ceshi extends AdminController
{
    # 导入CSV格式测试
    public function import_csv()
    {
        if(request()->isPost())
        {
            # 定义字段
            $data['name'] = '';
            $data['telphone'] = '';
            $data['username'] = '';
            $data['phone'] = '';
            $insertData = PHPCsv::importCsv('file',$data,0);
            # 添加到数据库
            foreach ($insertData as $k => $v)
            {
                Csvceshi::create([
                    'name' => $v['name'],
                    'telphone' => $v['telphone'],
                    'username' => $v['username'],
                    'phone' => $v['phone'],
                ]);
            }
//            halt($insertData);
            $this->success('操作成功');
        }
        $list = Csvceshi::select()->toArray();
        return view('ceshi/import_csv',['list'=>$list]);
    }
}