<?php

namespace App\Pool;

use EasySwoole\Component\Singleton;
use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\RedisPool\Redis;

class CreateRedisPool
{
    use Singleton;

    //注册redis连接池，只能在mainServerCreate中用
    public function createRedis()
    {
        $conf=new RedisConfig();

        $redis_ini=\Yaconf::get('local_redis');

        $conf->setHost($redis_ini['host']);
        $conf->setPort($redis_ini['port']);
        $conf->setAuth($redis_ini['auth']);

        $redisPoolConfig=Redis::getInstance()->register('redis',$conf);
        $redisPoolConfig->setMinObjectNum(5);
        $redisPoolConfig->setMaxObjectNum(20);
        $redisPoolConfig->setAutoPing(10);

        return true;
    }
}
