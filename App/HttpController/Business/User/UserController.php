<?php

namespace App\HttpController\Business\User;

use App\HttpController\Business\BusinessBase;
use EasySwoole\DDL\Blueprint\Table;
use EasySwoole\DDL\DDLBuilder;
use EasySwoole\DDL\Enum\Character;
use EasySwoole\DDL\Enum\Engine;
use EasySwoole\Pool\Manager;
use EasySwoole\RedisPool\Redis;

class UserController extends BusinessBase
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

    //用户注册
    public function reg()
    {
        $redisObj=Redis::defer('redis');
        $redisObj->select(0);
        $redisObj->hSet('carsConfig','projectName','超酷的名字');
        $redisObj->hSet('carsConfig','logoRectangle','/static/image/logo/logo-rectangle.jpg?v=123');
        $redisObj->hSet('carsConfig','hotTel','4008-517-517');
        $redisObj->hSet('carsConfig','homeBanner',json_encode([
            '/static/image/banner/banner1.jpg?v=123',
            '/static/image/banner/banner2.jpg?v=123',
            '/static/image/banner/banner3.jpg?v=123',
            '/static/image/banner/banner4.jpg?v=123',
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





        $param=$this->request()->getRequestParam();

        $this->writeJson(200,$param);

        return true;
    }

    //用户登录
    public function login()
    {

    }













    private function mysqlRightWay()
    {
        $sql=DDLBuilder::table('users',function (Table $table)
        {
            $table->setTableComment('users表')
                ->setTableEngine(Engine::INNODB)
                ->setTableCharset(Character::UTF8MB4_GENERAL_CI);

            $table->colInt('id',11)->setColumnComment('用户主键')->setIsAutoIncrement()->setIsUnsigned()->setIsPrimaryKey();
            $table->colVarChar('username')->setColumnLimit(50)->setDefaultValue('')->setColumnComment('用户名称');
            $table->colVarChar('password')->setColumnLimit(50)->setDefaultValue('')->setColumnComment('用户密码');
            $table->colInt('phone',11)->setIsUnsigned()->setIsNotNull()->setColumnComment('手机号');
            $table->colInt('regTime',11)->setIsUnsigned()->setIsNotNull()->setColumnComment('注册时间');
            $table->indexNormal('phone_index','phone');
        });

        try
        {
            $obj=Manager::getInstance()->get('cars')->getObj();

            $obj->rawQuery($sql);

        }catch (\Throwable $e)
        {
            var_dump($e->getMessage());

        }finally
        {
            Manager::getInstance()->get('cars')->recycleObj($obj);
        }

        $this->writeJson(200,'ok','success');

        return true;
    }

    private function redisRightWay()
    {
        $obj=Redis::defer('redis');

        $obj->del('wanghan');

        return true;
    }
}
