<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class KplphpKbuilderCurd extends Command
{
    protected $appPath;
    public function __construct()
    {
        parent::__construct();
        $this->appPath = app_path();
    }

    protected function configure()
    {
        $this->setName('make:kplphp_kbuilder')
            ->addArgument('controller', Argument::OPTIONAL, "controller name")
            ->addArgument('model', Argument::OPTIONAL, "model name")
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
        if ($input->hasOption('app')) {
            $appName = $input->getOption('app');
        }
        $this->makeController($controllerName, $appName);
        $this->makeModel($modelName, $appName);
        $output->writeln($controllerName . ' controller create success');
        $output->writeln($appName . ' model create success');
    }

    // 创建控制器文件
    protected function makeController($controllerName, $appName)
    {
        $controllerStub = $this->appPath . 'command' . DIRECTORY_SEPARATOR . 'kpl' .DIRECTORY_SEPARATOR. 'Controller.kpl';
        $controllerStub = str_replace(['$controller', '$app'], [ucfirst($controllerName), strtolower($appName)], file_get_contents($controllerStub));
        $controllerPath = $this->appPath . $appName . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR;
        if (!is_dir($controllerPath)) {
            mkdir($controllerPath, 0777, true);
        }
        return file_put_contents( $controllerPath . $controllerName . '.php', $controllerStub);
    }
    // 创建模型文件
    public function makeModel($modelName, $appName)
    {
        $modelStub = $this->appPath .  'command' . DIRECTORY_SEPARATOR . 'kpl' .DIRECTORY_SEPARATOR. 'Model.kpl';
        $modelPath = $this->appPath . $appName . DIRECTORY_SEPARATOR . 'model';
        if (!is_dir($modelPath)) {
            mkdir($modelPath, 0777, true);
        }
        $modelStub = str_replace(['$model', '$app'], [ucfirst($modelName), strtolower($appName)], file_get_contents($modelStub));
        return file_put_contents($modelPath . DIRECTORY_SEPARATOR . $modelName . '.php', $modelStub);
    }
}
