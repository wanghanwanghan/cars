<?php

namespace App\Pool;

use EasySwoole\Component\Singleton;
use EasySwoole\Mysqli\Client;
use EasySwoole\Mysqli\Config;
use EasySwoole\Pool\AbstractPool;
use EasySwoole\Pool\Manager;

class CreateMysqlPoolForProjectDb extends AbstractPool
{
    use Singleton;

    protected $mysqlConf;

    protected $database='cars';

    public function __construct()
    {
        parent::__construct(new \EasySwoole\Pool\Config());

        $mysql_ini=\Yaconf::get('local_mysql');

        $mysqlConf = new Config([
            'host'     => $mysql_ini['host'],
            'port'     => $mysql_ini['port'],
            'user'     => $mysql_ini['user'],
            'password' => $mysql_ini['password'],
            'database' => $this->database,
            'timeout'  => $mysql_ini['timeout'],
            'charset'  => $mysql_ini['charset'],
        ]);

        $this->mysqlConf = $mysqlConf;
    }

    protected function createObject()
    {
        return new Client($this->mysqlConf);
    }

    //注册redis连接池，只能在mainServerCreate中用
    public function createMysql()
    {
        Manager::getInstance()->register(CreateMysqlPoolForProjectDb::getInstance(),$this->database);

        return true;
    }
}
