<?php

namespace App\HttpController\Admin;

use App\HttpController\Index;
use EasySwoole\Mysqli\QueryBuilder;

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

        $builder=new QueryBuilder();

        $res=$builder->where('username',$req['username'])->get('admin_users',1);


        $this->writeJson(200,[$req,$res]);

        return true;
    }


}
