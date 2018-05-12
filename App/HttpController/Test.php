<?php
namespace App\HttpController;

use App\ViewController;
use EasySwoole\Core\Swoole\ServerManager;


class Test extends ViewController
{
    function index()
    {
        $this->response()->write(Room::testSet());
        $this->response()->write("\n");
        $this->response()->write(Room::testGet());

        $this->fetch('Home/test');      # 对应模板: Views/index.html

    }


    /*
 * 请调用who，获取fd
 * http://ip:9501/push/index.html?fd=xxxx
 */
    function push()
    {
        $fd = intval($this->request()->getRequestParam('fd'));
        $info = ServerManager::getInstance()->getServer()->connection_info($fd);
        if(is_array($info)){

            ServerManager::getInstance()->getServer()->push($fd,'push in http at '.time());
        }else{
            $this->response()->write("fd {$fd} not exist");
        }
    }

}