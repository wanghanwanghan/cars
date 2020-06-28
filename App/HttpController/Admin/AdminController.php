<?php

namespace App\HttpController\Admin;

use App\HttpController\Index;
use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\Pool\Manager;
use wanghanwanghan\someUtils\control;

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

    //后台登录
    public function login()
    {
        $req=$this->request()->getRequestParam();

        try
        {
            $obj=Manager::getInstance()->get('cars')->getObj();

            $res=$obj->queryBuilder()
                ->where('username',$req['username'])
                ->get('admin_users',1);

            $res=$obj->execBuilder();

            $res=current($res);

        }catch (\Throwable $e)
        {
            $res=[];
        }finally
        {
            Manager::getInstance()->get('cars')->recycleObj($obj);
        }

        if ($req['password']==$res['password'])
        {
            $this->writeJson(200,$res);
        }else
        {
            $this->writeJson(201,[]);
        }

        return true;
    }

    //上传图片
    public function uploadImg()
    {
        $data=$this->request()->getUploadedFiles();

        foreach ($data as $one)
        {
            $ext=$one->getClientMediaType();

            $ext=explode('/',$ext);

            $ext=".{$ext[1]}";

            $filename='/static/image/carImg/'.control::getUuid(12).$ext;

            $path=BASEPATH.$filename;

            $one->moveTo($path);
        }

        $this->writeJson(200,$filename);

        return true;
    }











}
