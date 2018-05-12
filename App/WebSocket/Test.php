<?php
namespace App\WebSocket;


use App\WebSocket\Logic\Room;
use EasySwoole\Core\Socket\Response;
use EasySwoole\Core\Socket\AbstractInterface\WebSocketController;
use EasySwoole\Core\Swoole\ServerManager;
use EasySwoole\Core\Swoole\Task\TaskManager;

class Test extends WebSocketController
{



    /**
     * 进入房间
     */
    public function intoRoom()
    {
        // TODO: 业务逻辑自行实现
        $param = $this->request()->getArg('data');
        $userId = $param['userId'];
        $roomId = $param['roomId'];

        $fd = $this->client()->getFd();
        Room::login($userId, $fd);
        Room::joinRoom($roomId, $fd);
        $this->response()->write("加入{$roomId}房间");
    }



    /**
     * 发送信息到房间
     */
    public function sendToRoom()
    {
        // TODO: 业务逻辑自行实现
        $param = $this->request()->getArg('data');
        $message = $param['message'];
        $roomId = $param['roomId'];

        //异步推送
        TaskManager::async(function ()use($roomId, $message){
            $list = Room::selectRoomFd($roomId);
            foreach ($list as $fd) {
                ServerManager::getInstance()->getServer()->push($fd, $message);
            }
        });
    }

    /**
     * 发送私聊
     */
    public function sendToUser()
    {
        // TODO: 业务逻辑自行实现
        $param = $this->request()->getArg('data');
        $message = $param['message'];
        $userId = $param['userId'];

        //异步推送
        TaskManager::async(function ()use($userId, $message){
            $fdList = Room::getUserFd($userId);

//            $this->response()->write($fdList);

            foreach ($fdList as $fd) {
                ServerManager::getInstance()->getServer()->push($fd, $message);
            }
        });
    }

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