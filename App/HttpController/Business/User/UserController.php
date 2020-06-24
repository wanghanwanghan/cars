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

    public function index()
    {
        $obj=Redis::defer('redis');

        $obj->select(0);

        $obj->set('wanghan','胡康菲',200);

        $this->writeJson(200,['胡康菲'],'success');
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
