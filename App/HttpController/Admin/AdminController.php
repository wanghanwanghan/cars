<?php

namespace App\HttpController\Admin;

use App\HttpController\Index;
use EasySwoole\DDL\Blueprint\Table;
use EasySwoole\DDL\DDLBuilder;
use EasySwoole\DDL\Enum\Character;
use EasySwoole\DDL\Enum\Engine;
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
                ->fields('id,pid,name')
                ->get('china_area');

            $res=$obj->execBuilder();

            var_dump($res);

        }catch (\Throwable $e)
        {
            $res=[];
        }finally
        {
            Manager::getInstance()->get('cars')->recycleObj($obj);
        }

        $this->writeJson(200,$res);












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










    //建表
    public function createTable()
    {
        //车辆类型表
        $sql[]=DDLBuilder::table('carType',function (Table $table)
        {
            $table->setTableComment('车辆类型表')->setTableEngine(Engine::INNODB)->setTableCharset(Character::UTF8MB4_GENERAL_CI);
            $table->colInt('id',11)->setColumnComment('主键')->setIsAutoIncrement()->setIsUnsigned()->setIsPrimaryKey();
            $table->colVarChar('carType')->setColumnLimit(50)->setDefaultValue('');
        });

        //车辆品牌表
        $sql[]=DDLBuilder::table('carBrand',function (Table $table)
        {
            $table->setTableComment('车辆品牌表')->setTableEngine(Engine::INNODB)->setTableCharset(Character::UTF8MB4_GENERAL_CI);
            $table->colInt('id',11)->setColumnComment('主键')->setIsAutoIncrement()->setIsUnsigned()->setIsPrimaryKey();
            $table->colVarChar('carBrand')->setColumnLimit(50)->setDefaultValue('');
        });

        //车辆牌照类型表
        $sql[]=DDLBuilder::table('carLicenseType',function (Table $table)
        {
            $table->setTableComment('车辆牌照类型表')->setTableEngine(Engine::INNODB)->setTableCharset(Character::UTF8MB4_GENERAL_CI);
            $table->colInt('id',11)->setColumnComment('主键')->setIsAutoIncrement()->setIsUnsigned()->setIsPrimaryKey();
            $table->colVarChar('carLicenseType')->setColumnLimit(50)->setDefaultValue('');
        });

        //车辆信息表
        $sql[]=DDLBuilder::table('info',function (Table $table)
        {
            $table->setTableComment('车辆信息表')->setTableEngine(Engine::INNODB)->setTableCharset(Character::UTF8MB4_GENERAL_CI);
            $table->colInt('id',11)->setColumnComment('主键')->setIsAutoIncrement()->setIsUnsigned()->setIsPrimaryKey();
            $table->colVarChar('brand')->setColumnLimit(50)->setDefaultValue('')->setColumnComment('品牌');
            $table->colVarChar('type')->setColumnLimit(50)->setDefaultValue('')->setColumnComment('类型');
            $table->colVarChar('label')->setColumnLimit(100)->setDefaultValue('')->setColumnComment('标签');
            $table->colVarChar('name')->setColumnLimit(50)->setDefaultValue('')->setColumnComment('型号');
            $table->colInt('price',11)->setIsUnsigned()->setDefaultValue(0)->setColumnComment('价格');
            $table->colDecimal('km_ext',5,2)->setIsUnsigned()->setDefaultValue(1)->setColumnComment('公里系数');
            $table->colInt('forfeitPrice',11)->setIsUnsigned()->setDefaultValue(0)->setColumnComment('违章押金');
            $table->colInt('damagePrice',11)->setIsUnsigned()->setDefaultValue(0)->setColumnComment('车损押金');
            $table->colVarChar('desc',255)->setDefaultValue('')->setColumnComment('描述');
            $table->colVarChar('license',50)->setDefaultValue('')->setColumnComment('车牌照');
            $table->colInt('level',11)->setIsUnsigned()->setDefaultValue(0)->setColumnComment('权重');
            $table->colInt('hot',11)->setIsUnsigned()->setDefaultValue(0)->setColumnComment('热度');
            $table->colVarChar('city')->setColumnLimit(50)->setDefaultValue('北京')->setColumnComment('所属城市');




        });







        //banner表
        $sql[]=DDLBuilder::table('banner',function (Table $table)
        {
            $table->setTableComment('banner表')->setTableEngine(Engine::INNODB)->setTableCharset(Character::UTF8MB4_GENERAL_CI);
            $table->colInt('id',11)->setColumnComment('主键')->setIsAutoIncrement()->setIsUnsigned()->setIsPrimaryKey();
            $table->colVarChar('image')->setColumnLimit(100)->setDefaultValue('')->setColumnComment('图片地址');
            $table->colTinyInt('isShow')->setIsUnsigned()->setDefaultValue(1)->setColumnComment('是否显示');
            $table->colTinyInt('level')->setIsUnsigned()->setDefaultValue(0)->setColumnComment('权重');
            $table->colTinyInt('type')->setIsUnsigned()->setDefaultValue(1)->setColumnComment('1是页面，2是公众号文章');
            $table->colVarChar('href')->setColumnLimit(255)->setDefaultValue('')->setColumnComment('跳转地址');
        });

        //================================ admin ================================

        //后台用户表
        $sql[]=DDLBuilder::table('admin_users',function (Table $table)
        {
            $table->setTableComment('后台用户表')->setTableEngine(Engine::INNODB)->setTableCharset(Character::UTF8MB4_GENERAL_CI);
            $table->colInt('id',11)->setColumnComment('主键')->setIsAutoIncrement()->setIsUnsigned()->setIsPrimaryKey();
            $table->colVarChar('username')->setColumnLimit(50)->setDefaultValue('')->setColumnComment('用户名');
            $table->colVarChar('password')->setColumnLimit(50)->setDefaultValue('')->setColumnComment('密码');
            $table->colVarChar('phone',11)->setIsNotNull()->setColumnComment('手机号');
        });











        foreach ($sql as $oneSql)
        {
            try
            {
                $obj=Manager::getInstance()->get('cars')->getObj();

                $obj->rawQuery($oneSql);

            }catch (\Throwable $e)
            {
                var_dump($e->getMessage());
            }finally
            {
                Manager::getInstance()->get('cars')->recycleObj($obj);
            }
        }

        return true;
    }
}
