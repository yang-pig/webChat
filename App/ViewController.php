<?php

namespace App;

use EasySwoole\Core\Component\Di;
use EasySwoole\Core\Component\SysConst;
use EasySwoole\Core\Http\AbstractInterface\Controller;
use EasySwoole\Core\Http\Request;
use EasySwoole\Core\Http\Response;
use think\Template;

/**
 * 视图控制器
 * Class ViewController
 * @author  : evalor <master@evalor.cn>
 * @package App
 */
abstract class ViewController extends Controller
{
    protected $view;

    /**
     * 初始化模板引擎
     * ViewController constructor.
     * @param string   $actionName
     * @param Request  $request
     * @param Response $response
     */
    function __construct(string $actionName, Request $request, Response $response)
    {
        $this->view = new Template();
        $this->view->config([
            'view_path'  => EASYSWOOLE_ROOT . '/Views/',              # 模板文件目录
            'cache_path' => EASYSWOOLE_ROOT . "/Temp/templates_c/",               # 模板编译目录
        ]);

        parent::__construct($actionName, $request, $response);
    }

    /**
     * 输出模板到页面
     * @param  string|null $template 模板文件
     * @param array        $vars     模板变量值
     * @param array        $config   额外的渲染配置
     * @author : evalor <master@evalor.cn>
     */
    function fetch($template, $vars = [], $config = [])
    {
        ob_start();
        $this->view->fetch($template, $vars, $config);
        $content = ob_get_clean();
        $this->response()->write($content);
    }


    function  assign($name,$data) {
        $this->view->assign($name,$data);
    }

}