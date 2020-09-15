<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Config;

class KplphpCurd extends Command
{
    protected $appPath;
    protected $views = ['index'];
    public function __construct()
    {
        parent::__construct();
        $this->appPath = app_path();
    }

    protected function configure()
    {
        $this->setName('make:kplphp_curd')
            ->addArgument('controller', Argument::OPTIONAL, "controller name")
            ->addArgument('model', Argument::OPTIONAL, "model name")
            ->addArgument('validate', Argument::OPTIONAL, "validate name")
            ->addOption('app', null, Option::VALUE_REQUIRED, 'app name')
            ->setDescription('Create curd option controller model --app?');
    }

    protected function execute(Input $input, Output $output)
    {
        $appName = 'admin';
        $controllerName = trim($input->getArgument('controller'));
        if (!$controllerName) {
            $output->writeln('Controller Name Must Set');exit;
        }
        $modelName = trim($input->getArgument('model'));
        if (!$modelName) {
            $output->writeln('Model Name Must Set');exit;
        }
        $validateName = trim($input->getArgument('validate'));
        if (!$validateName) {
            $output->writeln('Validate Name Must Set');exit;
        }
        if ($input->hasOption('app')) {
            $appName = $input->getOption('app');
        }
        $this->makeController($controllerName, $appName);
        $output->writeln($controllerName . ' controller create success');
        $this->makeModel($modelName, $appName);
        $output->writeln($appName . ' model create success');
        $this->makeValidate($validateName, $appName);
        $output->writeln($validateName . ' validate create success');
        $this->makeView($controllerName, $appName);
        $output->writeln($appName . ' view create success');
    }

    // 创建控制器文件
    protected function makeController($controllerName, $appName)
    {
        $controllerKpl = $this->appPath . 'command' . DIRECTORY_SEPARATOR . 'kpl' .DIRECTORY_SEPARATOR. 'Controller.kpl';
        $controllerKpl = str_replace(['$controller', '$app'], [ucfirst($controllerName), strtolower($appName)], file_get_contents($controllerKpl));
        $controllerPath = $this->appPath . $appName . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR;
        if (!is_dir($controllerPath)) {
            @mkdir($controllerPath, 0777, true);
        }
        return @file_put_contents( $controllerPath . $controllerName . '.php', $controllerKpl);
    }
    // 创建模型文件
    public function makeModel($modelName, $appName)
    {
        $modelKpl = $this->appPath .  'command' . DIRECTORY_SEPARATOR . 'kpl' .DIRECTORY_SEPARATOR. 'Model.kpl';
        $modelPath = $this->appPath . $appName . DIRECTORY_SEPARATOR . 'model';
        if (!is_dir($modelPath)) {
            @mkdir($modelPath, 0777, true);
        }
        $modelKpl = str_replace(['$model', '$app'], [ucfirst($modelName), strtolower($appName)], file_get_contents($modelKpl));
        return @file_put_contents($modelPath . DIRECTORY_SEPARATOR . $modelName . '.php', $modelKpl);
    }
    // 创建验证器
    public function makeValidate($validateName, $appName)
    {
        $validateKpl = $this->appPath .  'command' . DIRECTORY_SEPARATOR . 'kpl' .DIRECTORY_SEPARATOR. 'Validate.kpl';
        $validatePath = $this->appPath . $appName . DIRECTORY_SEPARATOR . 'validate';
        if (!is_dir($validatePath)) {
            @mkdir($validatePath, 0777, true);
        }
        $validateKpl = str_replace(['$validate', '$app'], [ucfirst($validateName), strtolower($appName)], file_get_contents($validateKpl));
        return @file_put_contents($validatePath . DIRECTORY_SEPARATOR . $validateName . '.php', $validateKpl);
    }
    // 创建视图
    public function makeView($controllerName, $appName)
    {
        $viewKpl = $this->appPath .  'command' . DIRECTORY_SEPARATOR . 'kpl' .DIRECTORY_SEPARATOR. 'View.kpl';
        $viewPath   = (Config::get('view.view_dir_name') ?   Config::get('view.view_dir_name') .'/'. $appName . DIRECTORY_SEPARATOR : $this->appPath . $appName . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR) . strtolower($controllerName);
        if (!is_dir($viewPath)) {
            @mkdir($viewPath, 0777, true);
        }
        foreach ($this->views as $view) {
            @file_put_contents($viewPath . DIRECTORY_SEPARATOR . $view .'.html', file_get_contents($viewKpl));
        }
    }
}
