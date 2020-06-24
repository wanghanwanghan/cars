<?php

namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\Controller;
use wanghanwanghan\someUtils\control;

class Index extends Controller
{
    public function index()
    {

    }

    public function test()
    {
        $this->writeJson(200,control::install(),'success');
    }
}