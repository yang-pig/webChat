<?php
namespace App\WebSocket;


use EasySwoole\Core\Socket\Response;
use EasySwoole\Core\Socket\AbstractInterface\WebSocketController;
use EasySwoole\Core\Swoole\Task\TaskManager;

class Test extends WebSocketController
{
    function actionNotFound(?string $actionName)
    {
        $this->response()->write("action call {$actionName} not found");
    }

    function hello()
    {
        $this->response()->write('call hello with arg:'.$this->request()->getArg('content'));

    }

    public function who(){
        $this->response()->write('your fd is '.$this->client()->getFd());
    }

    function delay()
    {
        $this->response()->write('this is delay action');
        $request = $this->request();
        $client = $this->client();
        //测试异步推送
        TaskManager::async(function ()use($client,$request){
            sleep(1);
            Response::response($client,'this is async task res'.time());
            Response::response($client,'this is async task res：'.$request->getArg('content'));
        });
    }
}