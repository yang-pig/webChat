<?php
namespace App;


use EasySwoole\Core\Socket\AbstractInterface\ParserInterface;
use EasySwoole\Core\Socket\Common\CommandBean;

class Parser implements ParserInterface
{

    public static function decode($raw, $client)
    {

        $command = new CommandBean();
        $json = json_decode($raw,1);
        $command->setControllerClass(\App\WebSocket\Test::class);
        $command->setAction($json['action']);
        $command->setArg('content',$json['content']);
        return $command;

    }

    public static function encode(string $raw, $client): ?string
    {
        // TODO: Implement encode() method.
        return $raw;
    }
}