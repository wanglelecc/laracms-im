<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/19
 * Time: 11:46
 */

namespace App\Sock\Parser;

use EasySwoole\Core\Socket\AbstractInterface\ParserInterface;
use EasySwoole\Core\Socket\Common\CommandBean;

class WebSocket implements ParserInterface
{
    public function decode($raw, $client)
    {
        $command = new CommandBean();
        $json = json_decode($raw,1);
        $command->setControllerClass(\App\Sock\Controller\WebSocketTest::class);
        $command->setAction($json['action']);
        $command->setArg('content', $json['content']);

        return $command;
    }

    public function encode(string $raw, $client, $commandBean): ?string
    {
        return $raw;
    }

}