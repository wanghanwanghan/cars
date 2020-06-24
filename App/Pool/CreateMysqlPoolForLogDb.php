<?php

namespace App\Pool;

use EasySwoole\Component\Singleton;
use EasySwoole\Mysqli\Client;
use EasySwoole\Mysqli\Config;
use EasySwoole\Pool\AbstractPool;
use EasySwoole\Pool\Manager;

class CreateMysqlPoolForLogDb extends AbstractPool
{
    use Singleton;

    protected $mysqlConf;

    protected $database='logs';

    public function __construct()
    {
        parent::__construct(new \EasySwoole\Pool\Config());

        $mysqlConf = new Config([
            'host'     => '127.0.0.1',
            'port'     => 63306,
            'user'     => 'chinaiiss',
            'password' => 'zbxlbj@2018*()',
            'database' => $this->database,
            'timeout'  => 5,
            'charset'  => 'utf8mb4',
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
        Manager::getInstance()->register(CreateMysqlPoolForLogDb::getInstance(),$this->database);

        return true;
    }
}
