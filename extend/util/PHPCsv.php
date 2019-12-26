<?php
namespace util;
/* kplphp框架 https://gitee.com/JefferyCai/kplphp
   测试 = 导入数据
*/
class PHPCsv {

    /* kplphp框架 https://gitee.com/JefferyCai/kplphp
        line_number = 1  跳过第N行[开始],默认1第一行
        file_name = 文件filename
        import_data = 导入数据[定义导入的字段]
     */
    static public function importCsv($file_name,$import_data,$line_number=0)
    {
        $goods_list = array();
        $field_list = array_keys($import_data);
        if(!$file_name)return [0=>'请上传文件'];
        if(!$import_data)return [0=>'请定义导入字段数据'];
        $data = file($_FILES["{$file_name}"]['tmp_name']);
        $fixx = substr(strrchr($_FILES["{$file_name}"]['name'], '.'), 1);
        if($fixx != 'csv')
        {
            return [0=>'请上传csv格式'];
        }
        foreach ($data as $line) {
            $line=iconv('GB2312','UTF-8//IGNORE',$line);

            if($line_number == 0)   // 跳过第一行
            {
                $line_number++;
                continue;
            }
            $arr    = array();
            $buff   = '';
            $quote  = 0;
            $len    = strlen($line);
            for ($i = 0; $i < $len; $i++)
            {
                $char = $line[$i];
                if ('\\' == $char)
                {
                    $i++;
                    $char = $line[$i];

                    switch ($char)
                    {
                        case '"':
                            $buff .= '"';
                            break;
                        case '\'':
                            $buff .= '\'';
                            break;
                        case ',';
                            $buff .= ',';
                            break;
                        default:
                            $buff .= '\\' . $char;
                            break;
                    }
                }
                elseif ('"' == $char)
                {
                    if (0 == $quote)
                    {
                        $quote++;
                    }
                    else
                    {
                        $quote = 0;
                    }
                }
                elseif (',' == $char)
                {
                    if (0 == $quote)
                    {
                        if (!isset($field_list[count($arr)]))
                        {
                            continue;
                        }
                        $field_name = $field_list[count($arr)];
                        $arr[$field_name] = trim($buff);
                        $buff = '';
                        $quote = 0;
                    }
                    else
                    {
                        $buff .= $char;
                    }
                }
                else
                {
                    $buff .= $char;
                }
                if ($i == $len - 1)
                {
                    if (!isset($field_list[count($arr)]))continue;
                    $field_name = $field_list[count($arr)];
                    $arr[$field_name] = trim($buff);
                }
            }
            $goods_list[] = $arr;
        }
//        halt($goods_list);
        return $goods_list;
    }
}