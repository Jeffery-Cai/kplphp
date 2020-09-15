<?php
/**
 * 表格构建器 主要文件
 *
 */
namespace app\common\kbuilder\form;
use think\App;
use think\facade\Cookie;
use think\facade\Db;

class Kbuilder{
    protected $template;
    protected $kplurl;
    protected $where;
    protected $table;
    protected $id;
    protected $info;
    protected $bottomBtn = [];
    protected $editAddField = [];
    protected $vars = [];
    protected $list = [];

    public function __construct()
    {
        $this->initialize();
    }

    public function initialize()
    {
        # 重新定义kplphp默认请求地址
        $this->kplurl = [
            'index'=>'/'.parse_name(request()->controller()."/".request()->action()),
            'see'=>'/'.parse_name(request()->controller()."/see"),
            'add'=>'/'.parse_name(request()->controller()."/add"),
            'edit'=>'/'.parse_name(request()->controller()."/edit"),
            'del'=>'/'.parse_name(request()->controller()."/del"),
        ];
        $this->template = app()->getBasePath().'common/kbuilder/form/layout.html';
    }
    /**
     * @param array $btnlist == kplphp系统预设好四种按钮 【reset重置表单 submit提交表单 return返回上一页】
     * @return $this
     */
    public function setBottomBtn($btnlist=[])
    {
        if(empty($btnlist))$btnlist = ['reset'=>'重置','submit'=>'提交','return'=>'返回'];
        $this->bottomBtn = $btnlist;
        return $this;
    }
    public function setEditAddField($field=[])
    {
        $this->editAddField = $field;
        Cookie::forever('editAddField', json_encode($field));
        return $this;
    }

    /**
     * @param null $id == 获取信息ID值
     * @return \app\common\kbuilder\see\Kbuilder
     */
    public function getInfo($id=null)
    {
        $cacheFields = Cookie::get('editAddField');
        if($cacheFields)
        {
            $cacheFields = json_decode(Cookie::get('editAddField'));
        }else{
            $cacheFields = $this->editAddField;
        }
        $resultFields = [];
        foreach ($cacheFields as $k => $v)
        {
            $resultFields[$k]['type'] = $v[0];
            $resultFields[$k]['field'] = $v[1];
            $resultFields[$k]['title'] = $v[2];
            $resultFields[$k]['placeholder'] = $v[3];
            $resultFields[$k]['mean'] = $v[4];
            $resultFields[$k]['disabled'] = $v[5];
            $resultFields[$k]['setarr'] = isset($v[6])?$v[6]:[];
            $resultFields[$k]['exsetarr'] = isset($v[6])?implode(',',$v[6]):[];
            $resultFields[$k]['value'] = !empty($id) && !empty($v[1])?Db::name($this->table)->where(['id'=>$id])->value($v[1]):'';
            $resultFields[$k]['inline-css'] = isset($v[7])?$v[7]:'';
        }
        $this->id = $id;
        $this->info = $resultFields;
        return $this;
    }

    public function setTable($table='')
    {
        $this->table = $table;
        Cookie::forever('table', $table);
        return $this;
    }

    /**
     * 渲染模板输出
     * @return \think\response\View
     */
    public function view()
    {
        $vars = [
            'data'=>$this->info,
            'id'=>$this->id,
            'kplurl'=>$this->kplurl,
            'bottomBtn'=>$this->bottomBtn,
            'table'=>$this->table,
            'is_submit'=>'提交操作'
        ];
        return view($this->template,$vars);
    }

}