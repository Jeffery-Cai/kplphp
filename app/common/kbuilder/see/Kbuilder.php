<?php
/**
 * 表格构建器 主要文件
 */
namespace app\common\kbuilder\see;
use think\App;
use think\facade\Cookie;
use think\facade\Db;

class Kbuilder{
    protected $template;
    protected $where;
    protected $info;
    protected $table;
    protected $seeField;
    protected $vars = [];
    protected $list = [];

    public function __construct()
    {
        $this->initialize();
    }

    public function initialize()
    {
        $this->template = app()->getBasePath().'common/kbuilder/see/layout.html';
    }

    public function setSeeField($field=[])
    {
        $this->seeField = $field;
        Cookie::forever('seeField', json_encode($field));
        return $this;
    }

    public function setTable($table='')
    {
        $this->table = $table;
        Cookie::forever('table', $table);
        return $this;
    }

    /**
     * @param null $id == 获取信息ID值
     * @return $this
     */
    public function getInfo($id=null)
    {
        $cacheFields = Cookie::get('seeField');
        if($cacheFields)
        {
            $cacheFields = json_decode(Cookie::get('seeField'));
        }else{
            $cacheFields = $this->seeField;
        }
        $resultFields = [];
        foreach ($cacheFields as $k => $v)
        {
            $resultFields[$k]['field'] = $v[0];
            $resultFields[$k]['title'] = $v[1];
            $resultFields[$k]['mean'] = $v[2];
            $resultFields[$k]['css'] = $v[3];
            $resultFields[$k]['value'] = Db::name($this->table)->where(['id'=>$id])->value($v[0]);
        }
        $this->info = $resultFields;
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
            'seeField'=>$this->seeField,
            'table'=>$this->table
        ];
        return view($this->template,$vars);
    }
}