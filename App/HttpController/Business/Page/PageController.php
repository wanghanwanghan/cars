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
        $redisObj=Redis::defer('redis');
        $redisObj->select(0);
        $redisObj->hSet('carsConfig','projectName','超酷的名字');
        $redisObj->hSet('carsConfig','logoRectangle','/static/image/logo/logo-rectangle.jpg?v=123');
        $redisObj->hSet('carsConfig','hotTel','4008-517-517');
        $redisObj->hSet('carsConfig','homeBanner',json_encode([
            [
                'src'=>'/static/image/banner/banner1.jpg?v=123',
                'href'=>'/static/image/banner/banner1.jpg?v=123',
                'type'=>'image',
            ],
            [
                'src'=>'/static/image/banner/banner2.jpg?v=123',
                'href'=>'/static/image/banner/banner2.jpg?v=123',
                'type'=>'image',
            ],
            [
                'src'=>'/static/image/banner/banner3.jpg?v=123',
                'href'=>'/static/image/banner/banner3.jpg?v=123',
                'type'=>'image',
            ],
            [
                'src'=>'/static/image/banner/banner4.jpg?v=123',
                'href'=>'/static/image/banner/banner4.jpg?v=123',
                'type'=>'image',
            ],
        ]));
        $redisObj->hSet('carsConfig','homeModule',json_encode([
            [
                'title'=>'酷享自驾','subtext1'=>'你想要的','subtext2'=>'都在这里','image'=>'/static/image/homeModule/1.jpg?v=123'
            ],
            [
                'title'=>'尊享出行','subtext1'=>'专人专车','subtext2'=>'一应俱全','image'=>'/static/image/homeModule/2.jpg?v=123'
            ],
            [
                'title'=>'极速摩托','subtext1'=>'追求极致','subtext2'=>'畅快淋漓','image'=>'/static/image/homeModule/3.jpg?v=123'
            ],
            [
                'title'=>'安心托管','subtext1'=>'追求极致','subtext2'=>'畅快淋漓','image'=>'/static/image/homeModule/4.jpg?v=123'
            ],
            [
                'title'=>'精致车源','subtext1'=>'炫酷超跑','subtext2'=>'触手可及','image'=>'/static/image/homeModule/5.jpg?v=123'
            ],
            [
                'title'=>'超值长租','subtext1'=>'长期租赁','subtext2'=>'更多优惠','image'=>'/static/image/homeModule/6.jpg?v=123'
            ],
        ]));

        $res=$redisObj->hGetAll('carsConfig');

        foreach ($res as $key => $val)
        {
            if (json_decode($val,true) != null)
            {
                $res[$key]=json_decode($val,true);
            }
        }

        $this->writeJson(200,$res);
    }






}
