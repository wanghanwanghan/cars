<?php

namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\AbstractRouter;
use FastRoute\RouteCollector;

class Router extends AbstractRouter
{
    public function initialize(RouteCollector $routeCollector)
    {
        //全局模式拦截下,路由将只匹配Router.php中的控制器方法响应,将不会执行框架的默认解析
        $this->setGlobalMode(true);

        //测试路由
        $this->routeInTest($routeCollector);






    }

    //测试路由
    private function routeInTest(RouteCollector $routeCollector)
    {
        $routeCollector->addRoute(['POST'],'/test','index/test');
        $routeCollector->addRoute(['POST'],'/test123','/Business/User/UserController/index');

        return true;
    }
}
