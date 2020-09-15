<?php
if (!function_exists('ceshi')) {
    function ceshi()
    {
        halt('ceshi');
    }
}

if (!function_exists('plugin_action_exists')) {
    /**
     * 检查插件控制器是否存在某操作
     * @param string $name 插件名
     * @param string $controller 控制器
     * @param string $action 动作
     * @return bool
     */
    function plugin_action_exists($name = '', $controller = '', $action = '')
    {
        if (strpos($name, '/')) {
            list($name, $controller, $action) = explode('/', $name);
        }
        return method_exists("plugins\\{$name}\\controller\\{$controller}", $action);
    }
}

if (!function_exists('plugin_model_exists')) {
    /**
     * 检查插件模型是否存在
     * @param string $name 插件名
     * @return bool
     */
    function plugin_model_exists($name = '')
    {
        return class_exists("plugins\\{$name}\\model\\{$name}");
    }
}

if (!function_exists('plugin_validate_exists')) {
    /**
     * 检查插件验证器是否存在
     * @param string $name 插件名
     * @return bool
     */
    function plugin_validate_exists($name = '')
    {
        return class_exists("plugins\\{$name}\\validate\\{$name}");
    }
}

if (!function_exists('get_plugin_model')) {
    /**
     * 获取插件模型实例
     * @param  string $name 插件名
     * @return object
     */
    function get_plugin_model($name)
    {
        $class = "plugins\\{$name}\\model\\{$name}";
        return new $class;
    }
}

if (!function_exists('plugin_action')) {
    /**
     * 执行插件动作
     * 也可以用这种方式调用：plugin_action('插件名/控制器/动作', [参数1,参数2...])
     * @param string $name 插件名
     * @param string $controller 控制器
     * @param string $action 动作
     * @param mixed $params 参数
     * @return mixed
     */
    function plugin_action($name = '', $controller = '', $action = '', $params = [])
    {
        if (strpos($name, '/')) {
            $params = is_array($controller) ? $controller : (array)$controller;
            list($name, $controller, $action) = explode('/', $name);
        }
        if (!is_array($params)) {
            $params = (array)$params;
        }
        $class = "addons\\{$name}\\controller\\{$controller}";
        $obj = new $class;
        return call_user_func_array([$obj, $action], $params);
    }
}