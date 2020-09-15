<?php
/**
 * 表格构建器 主要文件
 */
namespace app\common\kbuilder\table;
use think\App;
use think\facade\Cookie;

class Kbuilder{
    protected $template;
    protected $kplurl;
    protected $table;
    protected $searchKey = [];
    protected $seeField  = [];
    protected $editAddField = [];
    protected $rightBtn = [];
    protected $where = [];
    protected $vars = [];
    protected $list = [];
    protected $datalimit = 20;
    public function __construct()
    {
        $this->initialize();
    }

    public function initialize()
    {
        # 重新定义kplphp默认请求地址
        $this->kplurl = [
            'index'=>'/'.parse_name(request()->controller()."/".request()->action()),
            'add'=>'/'.parse_name(request()->controller()."/add"),
            'see'=>'/'.parse_name(request()->controller()."/see"),
            'edit'=>'/'.parse_name(request()->controller()."/edit"),
            'del'=>'/'.parse_name(request()->controller()."/del"),
        ];
        # 重新定义模板路径地址
        $this->template = app()->getBasePath().'common/kbuilder/table/layout.html';
    }

    /**
     * @param array $btnlist == kplphp系统预设好四种按钮 【add添加, see查看, edit编辑, del删除】
     * @return $this
     */
    public function setRightBtn($btnlist=[])
    {
        if(empty($btnlist))$btnlist = ['add'=>'添加','see'=>'查看','edit'=>'编辑','del'=>'删除'];
        $this->rightBtn = $btnlist;
        return $this;
    }

    public function setTable($table='')
    {
        $this->table = $table;
        Cookie::forever('table', $table);
        return $this;
    }

    public function setSeeField($field=[])
    {
        $this->seeField = $field;
        Cookie::forever('seeField', json_encode($field));
        return $this;
    }

    public function setEditAddField($field=[])
    {
        $this->editAddField = $field;
        Cookie::forever('editAddField', json_encode($field));
        return $this;
    }

    /**
     * 设置搜索字段 [已经废弃]
     * @param string $key  == 键
     * @param string $title == 标题
     * @return $this
     */
    public function setSearchField($data=[])
    {
        # 处理|号作为where条件
        $this->searchKey = $data;
        return $this;
    }


    /**
     * 设置字段数据列表 【单】
     * @param string $name
     * @param string $title
     * @param string $type
     * @param string $default
     * @return $this
     */
    public function setDataColumn($name='',$title='',$type='',$default='',$width='',$sort=false,$align='left')
    {
        $field = $name;
        $table = '';
        if (strpos($name, '|')) {
            list($name, $field) = explode('|', $name);
            if (strpos($field, '.')) {
                list($table, $field) = explode('.', $field);
            }
        }

        $column = [
            'name'    => $name,
            'title'   => $title,
            'type'    => $type,
            'default' => $default,
            'field'   => $field,
            'table'   => $table,
            'sort'    => $sort,
            'align'   => $align,
            'width'   => $width
        ];
        $args   = array_slice(func_get_args(), 7);
        $column = array_merge($column, $args);
        # 判断只要有checkbox，就自动生成个全选按钮
        if($column['name'] == 'checkbox')
        {
            $this->vars['checkbox'] = 1;
        }
        $this->vars['columns'][] = $column; # 列
        $this->vars['fieldsTitle'][] = $title; # 键[配合datatables的渲染方式]
        $this->vars['fieldsValue'][] = $field; # 值[配合datatables的渲染方式]
        return $this;
    }

    /**
     * 设置字段数据列表 【多】
     * @param array $columns
     * @return $this
     */
    public function setDataColumns($columns=[])
    {
        if (!empty($columns)) {
            foreach ($columns as $column) {
                call_user_func_array([$this, 'setDataColumn'], $column);
            }
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function setDataLimit($limit=20)
    {
        $this->datalimit = $limit;
        return $this;
    }

    /**
     * 设置渲染数据
     * @param array $list
     * @return $this
     */
    public function setDataList($list,$count=10,$code=0,$msg='数据列表')
    {
        $this->list = ['code'=>$code,'msg'=>$msg,'count'=>$count,'list'=>$list];
        return $this;
    }

    /**
     * 渲染模板输出
     * @return \think\response\View
     */
    public function view()
    {
        if(request()->isPost())
        {
            echo json_encode(['data'=>$this->list]);exit;
        }
        $vars = [
            'data'=>$this->list,
            'kplurl'=>$this->kplurl,
            'seeField'=>$this->seeField,
            'table'=>$this->table,
            'searchKey'=>$this->searchKey,
            'rightBtn'=>$this->rightBtn,
            'columns'=>$this->vars['columns'],
            'datalimit' =>$this->datalimit,
            'checkbox'=>isset($this->vars['checkbox'])?1:0,
            'fieldsTitle'=>$this->vars['fieldsTitle'],
            'fieldsValue'=>$this->vars['fieldsValue'],
        ];

        return view($this->template,$vars);
    }
}