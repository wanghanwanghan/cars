<?php

namespace App\HttpController\Business\Page;

use App\HttpController\Business\BusinessBase;
use EasySwoole\RedisPool\Redis;

class PageController extends BusinessBase
{
    public function onRequest(?string $action): ?bool
    {
        parent::onRequest($action);

        return true;
    }

    public function afterAction(?string $actionName): void
    {
        parent::afterAction($actionName);
    }

    //首页
    public function home()
    {
        $redisObj=Redis::defer('local_redis');
        $redisObj->select(0);
        $res=$redisObj->hGetAll('carsConfig');





        $this->writeJson(200,$res);
    }






}
