<?php

namespace App\HttpController\Business\Page;

use App\HttpController\Business\BusinessBase;
use wanghanwanghan\someUtils\control;

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
        $this->writeJson(200,['uuid'=>control::getUuid()],'小超垃圾');

        return true;
    }

    //酷享自驾
    public function sportsCar()
    {

    }



















}
