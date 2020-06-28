<?php

namespace EasySwoole\EasySwoole;

use App\Pool\CreateRedisPool;
use App\Pool\CreateMysqlPoolForLogDb;
use App\Pool\CreateMysqlPoolForProjectDb;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
    }

    public static function mainServerCreate(EventRegister $register)
    {
        //最外层目录
        define('BASEPATH',__DIR__.DIRECTORY_SEPARATOR);

        //注册redis连接池
        CreateRedisPool::getInstance()->createRedis();

        //注册mysql连接池
        CreateMysqlPoolForProjectDb::getInstance()->createMysql();
        CreateMysqlPoolForLogDb::getInstance()->createMysql();




    }

    public static function onRequest(Request $request, Response $response): bool
    {
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
    }
}