<?php
/**
 * Created by PhpStorm.
 * User: wll
 * Date: 18-4-18
 * Time: 下午8:14
 */

namespace App\Sock\Parser;

use EasySwoole\Core\Socket\AbstractInterface\ParserInterface;
use EasySwoole\Core\Socket\Common\CommandBean;

class Tcp implements ParserInterface{

    /*
     * 假定，客户端与服务端都是明文传输。控制格式为 sericeName：actionName：args
     */
    public function decode($raw, $client)
    {
        // TODO: Implement decode() method.
        $list = explode(":", trim($raw));
        $bean = new CommandBean();
        $controller = array_shift($list);
        if($controller == 'test'){
            $bean->setControllerClass(\App\Sock\Controller\TcpTest::class);
        }
        $bean->setAction(array_shift($list));
        $bean->setArg('test', array_shift($list));

        return $bean;
    }

    public function encode(string $raw, $client, $commandBean): ?string
    {
        // TODO: Implement encode() method.
        return $raw."\n";
    }
}