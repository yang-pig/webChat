<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/1/9
 * Time: 下午1:04
 */

namespace EasySwoole;

use App\Socket\Parser\WebSocket;
use App\Utility\Redis;
use App\WebSocket\Logic\Room;
use \EasySwoole\Core\AbstractInterface\EventInterface;
use EasySwoole\Core\Component\Di;
use EasySwoole\Core\Component\Logger;
use EasySwoole\Core\Swoole\EventHelper;
use \EasySwoole\Core\Swoole\ServerManager;
use \EasySwoole\Core\Swoole\EventRegister;
use \EasySwoole\Core\Http\Request;
use \EasySwoole\Core\Http\Response;
use think\Db;

Class EasySwooleEvent implements EventInterface {

    public static function frameInitialize(): void
    {
        date_default_timezone_set('Asia/Shanghai');
        //获得数据库配置
        $dbConf = Config::getInstance()->getConf('database');
        //全局初始化
        Db::setConfig($dbConf);




    }

    public static function mainServerCreate(ServerManager $server,EventRegister $register): void
    {
        EventHelper::registerDefaultOnMessage($register,\App\Parser::class);
        //注册onClose事件
        $register->add($register::onClose, function (\swoole_server $server, $fd, $reactorId) {
            //清除Redis fd的全部关联
            Room::close($fd);
        });

        // 注册Redis 从Config中读取Redis配置
        Di::getInstance()->set('REDIS', new Redis(Config::getInstance()->getConf('REDIS')));
    }

    public static function onRequest(Request $request,Response $response): void
    {

//        $request->withAttribute('requestTime', microtime(true));
    }

    public static function afterAction(Request $request,Response $response): void
    {
//        //从请求里获取之前增加的时间戳
//        $reqTime = $request->getAttribute('requestTime');
//        //计算一下运行时间
//        $runTime = round(microtime(true) - $reqTime, 3);
//        //获取用户IP地址
//        $ip = ServerManager::getInstance()->getServer()->connection_info($request->getSwooleRequest()->fd);
//
//        //拼接一个简单的日志
//        $logStr = ' | '.$ip['remote_ip'] .' | '. $runTime . '|' . $request->getUri() .' | '.
//            $request->getHeader('user-agent')[0];
//        //判断一下当执行时间大于1秒记录到 slowlog 文件中，否则记录到 access 文件
//        if($runTime > 1){
//
//            Logger::getInstance()->log($logStr, 'slowlog');
//        }else{
//            Logger::getInstance()->log($logStr,'access');
//        }
    }
}