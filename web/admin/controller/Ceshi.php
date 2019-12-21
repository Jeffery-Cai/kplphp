<?php
# 测试类
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