<?php

namespace App\HttpController\Admin;

use App\HttpController\Index;
use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\Pool\Manager;

class AdminController extends Index
{
    //继承这个主要是为了可以writeJson

    //也是为了onRequest
    public function onRequest(?string $action): ?bool
    {
        parent::onRequest($action);

        return true;
    }

    //还有afterAction
    public function afterAction(?string $actionName): void
    {
        parent::afterAction($actionName);
    }

    public function login()
    {
        $req=$this->request()->getRequestParam();

        try
        {
            $obj=Manager::getInstance()->get('cars')->getObj();

            $obj->queryBuilder()->where('username',$req['username'])->get('admin_users',1);

            $res=$obj->execBuilder();

            $res=current($res);

        }catch (\Throwable $e)
        {
            $res=[];
        }finally
        {
            Manager::getInstance()->get('cars')->recycleObj($obj);
        }

        $req['password']==$res['password'] ? $code=200 : $code=201;

        $this->writeJson($code,$res);

        return true;
    }


}
