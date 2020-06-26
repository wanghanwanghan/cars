<?php

namespace App\HttpController\Business\User;

use App\HttpController\Business\BusinessBase;

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

    }

    //用户登录
    public function login()
    {

    }








}
