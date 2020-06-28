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

        //跑车小程序路由
        $this->routeForCars($routeCollector);

        //跑车小程序后台管理系统路由
        $this->routeForCarsAdmin($routeCollector);

        //测试路由
        $this->routeForTest($routeCollector);
    }

    //跑车小程序路由
    private function routeForCars(RouteCollector $routeCollector)
    {
        //首屏
        $routeCollector->addRoute(['POST'],'/v1/home','/Business/Page/PageController/home');

        //酷享自驾
        $routeCollector->addRoute(['POST'],'/v1/sportsCar','/Business/Page/PageController/sportsCar');















        //注册
        $routeCollector->addRoute(['POST'],'/v1/userReg','/Business/User/UserController/reg');
        //登录
        $routeCollector->addRoute(['POST'],'/v1/userLogin','/Business/User/UserController/login');
    }

    //跑车小程序后台管理系统路由
    private function routeForCarsAdmin(RouteCollector $routeCollector)
    {
        //登录
        $routeCollector->addRoute(['POST'],'/admin/login','/Admin/AdminController/login');





    }

    //测试路由
    private function routeForTest(RouteCollector $routeCollector)
    {
        $routeCollector->addRoute(['POST'],'/test','Index/test');

        return true;
    }
}
