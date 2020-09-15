<?php
/*----------------------------------------------------------------
 * 版权所有 2019~2020 极盾工作室  kplphp地址[ http://www.kplphp.com ]
 * 作者由JefferyCai码云所创造 [ https://gitee.com/JefferyCai ]
 * 当前码云地址 与 操作文档都在 [ https://gitee.com/JefferyCai/kplphp ]
 * QQ群请加 972703635 [ https://jq.qq.com/?_wv=1027&k=5YnmIH8 ]，如有更多服务，请单独加群主: 1345199080
 * 测试功能
----------------------------------------------------------------*/
namespace app\admin\controller;
use app\common\controller\Kbuilder;
use app\AdminController;
use app\admin\model\Csvceshi;
use think\App;
use think\facade\Config;
use util\PHPCsv;

class Ceshi extends AdminController
{
    # 首页一键快速[默认]   前后端分离
    public function index()
    {
        # 查询条件[自己编写条件的判断便可]
        $w = [];
        $count = Csvceshi::where($w)->count();
        $list = Csvceshi::where($w)->page(input('page',1),input('limit',20))->select();
        # 搜索展示
        $searchField =  [
            ['name','text', '姓名','请输入姓名'],
            ['telphone','text', '学校电话','请输入学校电话'],
            ['username','text', '学生姓名','请输入学生姓名'],
            ['phone', 'text','手机号码','请输入手机号码'],
            ['status', 'select','状态','请输入手机号码',[''=>'选择状态',0=>'关闭',1=>'开启']],
            ['status', 'select2','状态[可搜索]','',[''=>'选择状态',0=>'关闭',1=>'开启',2=>'搜索其他东西啦']],
            ['create_time', 'time','创建时间','yyyy年MM月dd日',''],
        ];
        # 查看页面显示的字段
        $seeField = [
            ['id', 'ID','','col-sm-2'],
            ['name', '姓名','','col-sm-2'],
            ['telphone', '学校电话','','col-sm-2'],
            ['username', '学生姓名','','col-sm-2'],
            ['phone', '手机号码','','col-sm-2'],
        ];
        # 操作[编辑/添加]所需要的字段
        $editAddField = [
            ['text','name', '姓名','placeholder','表单类型为: text',''],
            ['text','telphone', '学校电话','placeholder','表单类型为: text','disabled'],
            ['password','password', '校园网密码','placeholder','表单类型为: password',''],
            ['switch','status', '状态','placeholder','表单类型为: radio',''],
            ['select','hobby1', '是否喜欢女性还是男性','placeholder','表单类型为: select','',[0=>'喜欢男的',1=>'喜欢女的',2=>'两个都喜欢']],
            ['select2','hobby2', '是否喜欢女性还是男性','placeholder','表单类型为: select2','',[0=>'喜欢男的',1=>'喜欢女的',2=>'两个都喜欢']],
            ['multiple_use','hobby3', '是否喜欢女性还是男性','placeholder','表单类型为: multiple_use','',[0=>'喜欢男的',1=>'喜欢女的',2=>'两个都喜欢'],'custom-control-inline'],
            ['checkboxs','hobby4', '喜欢哪些类型的爱好','placeholder','表单类型为: checkboxs','',[0=>'篮球',1=>'羽毛球',2=>'足球',3=>'橄榄球'],'custom-control-inline'],
            ['radio','hobby5', '最喜欢哪一个类型的爱好','placeholder','表单类型为: radio','',[0=>'篮球',1=>'羽毛球',2=>'足球',3=>'橄榄球'],'custom-control-inline'],
            ['tags','hobby', '请定义自己的标签','请输入','表单类型为: tags','',[0=>'KPLPHP',1=>'kplphp',2=>'HTML5',3=>'CSS3']],
            ['time','start_time', '选择自己的时间标签','请输入','表单类型为: time',''],
            ['datetime','start_time1', '选择自己的时间标签','请输入','表单类型为: datetime',''],
            ['image','touxiang', '上传头像[单文件]','请上传头像','表单类型为: image',''],
            ['image','touxiang1', '上传图片[单文件]','请上传头像','表单类型为: image',''],
            ['images','images', '上传多图片[多文件]','请上传多个美女图片','表单类型为: image',''],
        ];
        # 列表展示的字段
        $columns = [
            ['checkbox', '全选'],
            ['id', 'ID','','','80',true,'center'],
            ['name', '学校名称','edit','','120',true],
            ['telphone', '电话','','','120',true],
            ['username', '姓名','','','120',true],
            ['phone', '手机号码','','','120',true],
            ['name', '学校名称','','','120'],
            ['telphone', '电话','','','120',true],
            ['username', '姓名','','','120',true],
            ['phone', '手机号码','','','',true],
            ['rightbtn', '操作','','','200',false,'center'],
        ];
        return Kbuilder::sets('table')
            ->setEditAddField($editAddField)
            ->setSearchField($searchField)
            ->setSeeField($seeField)
            ->setRightBtn(['add'=>'添加','see'=>'查看','edit'=>'编辑','del'=>'删除'])
            ->setDataColumns($columns) # 设置字段数据列表
            ->setTable('csvceshi')
            ->setDataList($list,$count) # 设置渲染数据
            ->view();
    }

    # 首页一键 form表单
    /**
     * 以下的菜单需要自己添加节点，再去尝试，在这里只是做个示例
     */
    public function index_form()
    {
        # 查询条件[自己编写条件的判断便可]
//        $id = null;  #【==null 没有渲染数据，可用于空白表单进行提交】
        $id = 12;  #【!=null 没有渲染数据，可用于有值表单进行提交】
        # 操作[编辑/添加]所需要的字段
        $editAddField = [
            ['text','name', '姓名','placeholder','表单类型为: text',''],
            ['text','telphone', '学校电话','placeholder','表单类型为: text','disabled'],
            ['password','password', '校园网密码','placeholder','表单类型为: password',''],
            ['switch','status', '状态','placeholder','表单类型为: radio',''],
            ['select','hobby1', '是否喜欢女性还是男性','placeholder','表单类型为: select','',[0=>'喜欢男的',1=>'喜欢女的',2=>'两个都喜欢']],
            ['select2','hobby2', '是否喜欢女性还是男性','placeholder','表单类型为: select2','',[0=>'喜欢男的',1=>'喜欢女的',2=>'两个都喜欢']],
            ['multiple_use','hobby3', '是否喜欢女性还是男性','placeholder','表单类型为: multiple_use','',[0=>'喜欢男的',1=>'喜欢女的',2=>'两个都喜欢'],'custom-control-inline'],
            ['checkboxs','hobby4', '喜欢哪些类型的爱好','placeholder','表单类型为: checkboxs','',[0=>'篮球',1=>'羽毛球',2=>'足球',3=>'橄榄球'],'custom-control-inline'],
            ['radio','hobby5', '最喜欢哪一个类型的爱好','placeholder','表单类型为: radio','',[0=>'篮球',1=>'羽毛球',2=>'足球',3=>'橄榄球'],'custom-control-inline'],
            ['tags','hobby', '请定义自己的标签','请输入','表单类型为: tags','',[0=>'KPLPHP',1=>'kplphp',2=>'HTML5',3=>'CSS3']],
            ['time','start_time', '选择自己的时间标签','请输入','表单类型为: time',''],
            ['datetime','start_time1', '选择自己的时间标签','请输入','表单类型为: datetime',''],
            ['image','touxiang', '上传头像[单文件]','请上传头像','表单类型为: image',''],
            ['image','touxiang1', '上传图片[单文件]','请上传头像','表单类型为: image',''],
            ['images','images', '上传多图片[多文件]','请上传多个美女图片','表单类型为: image',''],
        ];
        return Kbuilder::sets('form')
            ->setEditAddField($editAddField)
            ->setTable('csvceshi')
            ->setBottomBtn(['submit'=>'提交','return'=>'返回'])
            ->getInfo($id) # 设置渲染数据
            ->view();
    }

    # 首页一键 see查看
    /**
     * 以下的菜单需要自己添加节点，再去尝试，在这里只是做个示例
     */
    public function index_see()
    {
        # 查询条件[自己编写条件的判断便可]
        $id = 13;
        # 查看页面显示的字段
        $seeField = [
            ['id', 'ID','','col-sm-2'],
            ['name', '姓名','','col-sm-2'],
            ['telphone', '学校电话','','col-sm-2'],
            ['username', '学生姓名','','col-sm-2'],
            ['phone', '手机号码','','col-sm-2'],
        ];
        return Kbuilder::sets('see')
            ->setSeeField($seeField)
            ->setTable('csvceshi')
            ->getInfo($id) # 设置渲染数据
            ->view();
    }

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