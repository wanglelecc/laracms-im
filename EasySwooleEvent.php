<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/1/9
 * Time: 下午1:04
 */

namespace EasySwoole;

use \EasySwoole\Core\AbstractInterface\EventInterface;
use EasySwoole\Core\Swoole\EventHelper;
use \EasySwoole\Core\Swoole\ServerManager;
use \EasySwoole\Core\Swoole\EventRegister;
use \EasySwoole\Core\Http\Request;
use \EasySwoole\Core\Http\Response;
use EasySwoole\Core\Swoole\Task\TaskManager;
use EasySwoole\Core\Utility\File;

Class EasySwooleEvent implements EventInterface {

    public static function frameInitialize(): void
    {
        // TODO: Implement frameInitialize() method.
        date_default_timezone_set('Asia/Shanghai');


    }

    public function loadConf($ConfPath)
    {
        $Conf = Config::getInstance();
        $files = File::scanDir($ConfPath);
        foreach ($files as $file) {
            $data = require_once $file;
            $Conf->setConf(strtolower(basename($file,'.php')), (array)$data);
        }
    }

    public static function mainServerCreate(ServerManager $server,EventRegister $register): void
    {
        // TODO: Implement mainServerCreate() method.
        $tcp = $server->addServer('tcp', 9502);
        EventHelper::registerDefaultOnReceive($tcp, new \App\Sock\Parser\Tcp(), function($errorType, $clientData, $client){
            // 第二个回调可有可无，当无法解析，或者解析出来的控制器不在的时候会调用
            TaskManager::async(function()use($client){
                sleep(3);
                \EasySwoole\Core\Socket\Response::response($client,'Bye');
                ServerManager::getInstance()->getServer()->close($client->getFd());
            });

            return "{$errorType} and going to close";
        });

        $tcp->set($tcp::onClose,function (\swoole_server $server, int $fd){
            var_dump('cccccccccccccccccccc rec'.' Fd:' . $fd);
        });


        EventHelper::registerDefaultOnMessage($register,new \App\Sock\Parser\WebSocket());


    }

    public static function onRequest(Request $request,Response $response): void
    {
        // TODO: Implement onRequest() method.
    }

    public static function afterAction(Request $request,Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}