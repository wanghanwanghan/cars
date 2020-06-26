<?php

namespace App\HttpController\Business\Page;

use App\HttpController\Business\BusinessBase;
use EasySwoole\DDL\Blueprint\Table;
use EasySwoole\DDL\DDLBuilder;
use EasySwoole\DDL\Enum\Character;
use EasySwoole\DDL\Enum\Engine;
use EasySwoole\Pool\Manager;
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
        $this->carsConfig();

        $this->createTable();

        $redisObj=Redis::defer('local_redis');
        $redisObj->select(0);

        $res=$redisObj->hGetAll('carsConfig');

        foreach ($res as $key => $val)
        {
            if (json_decode($val,true) != null)
            {
                $res[$key]=json_decode($val,true);
            }
        }




        $this->writeJson(200,$res);

        return true;
    }

    //酷享自驾
    public function sportsCar()
    {

    }




















    //建表
    public function createTable()
    {
        //用户表
        $sql[]=DDLBuilder::table('users',function (Table $table)
        {
            $table->setTableComment('users表')->setTableEngine(Engine::INNODB)->setTableCharset(Character::UTF8MB4_GENERAL_CI);
            $table->colInt('id',11)->setColumnComment('用户主键')->setIsAutoIncrement()->setIsUnsigned()->setIsPrimaryKey();
            $table->colVarChar('username')->setColumnLimit(50)->setDefaultValue('')->setColumnComment('用户名称');
            $table->colVarChar('password')->setColumnLimit(50)->setDefaultValue('')->setColumnComment('用户密码');
            $table->colInt('phone',11)->setIsUnsigned()->setIsNotNull()->setColumnComment('手机号');
            $table->colInt('regTime',11)->setIsUnsigned()->setIsNotNull()->setColumnComment('注册时间');
            $table->colVarChar('city')->setColumnLimit(50)->setDefaultValue('北京')->setColumnComment('主要用车城市');
            $table->colTinyInt('carLicense')->setIsUnsigned()->setDefaultValue(0)->setColumnComment('汽车驾照是否通过审核');
            $table->colTinyInt('motorLicense')->setIsUnsigned()->setDefaultValue(0)->setColumnComment('摩托车驾照是否通过审核');
            $table->colTinyInt('idCard')->setIsUnsigned()->setDefaultValue(0)->setColumnComment('身份证是否通过审核');
            $table->colVarChar('carLicenseImg')->setColumnLimit(100)->setDefaultValue('')->setColumnComment('汽车驾照图片');
            $table->colVarChar('motorLicenseImg')->setColumnLimit(100)->setDefaultValue('')->setColumnComment('摩托驾照图片');
            $table->colVarChar('idCardImg')->setColumnLimit(100)->setDefaultValue('')->setColumnComment('身份证图片');
            $table->indexNormal('phone_index','phone');
        });

        //品牌表
        $sql[]=DDLBuilder::table('brand',function (Table $table)
        {
            $table->setTableComment('品牌表')->setTableEngine(Engine::INNODB)->setTableCharset(Character::UTF8MB4_GENERAL_CI);
            $table->colInt('id',11)->setColumnComment('主键')->setIsAutoIncrement()->setIsUnsigned()->setIsPrimaryKey();
            $table->colVarChar('BrandName')->setColumnLimit(50)->setDefaultValue('')->setColumnComment('品牌名称');
        });

        //类型表
        $sql[]=DDLBuilder::table('type',function (Table $table)
        {
            $table->setTableComment('类型表')->setTableEngine(Engine::INNODB)->setTableCharset(Character::UTF8MB4_GENERAL_CI);
            $table->colInt('id',11)->setColumnComment('主键')->setIsAutoIncrement()->setIsUnsigned()->setIsPrimaryKey();
            $table->colVarChar('typeName')->setColumnLimit(50)->setDefaultValue('')->setColumnComment('类型名称');
        });

        //标签表
        $sql[]=DDLBuilder::table('label',function (Table $table)
        {
            $table->setTableComment('标签表')->setTableEngine(Engine::INNODB)->setTableCharset(Character::UTF8MB4_GENERAL_CI);
            $table->colInt('id',11)->setColumnComment('主键')->setIsAutoIncrement()->setIsUnsigned()->setIsPrimaryKey();
            $table->colVarChar('labelName')->setColumnLimit(50)->setDefaultValue('')->setColumnComment('标签名称');
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
//            $table->colInt('forfeitPrice',11)->setIsUnsigned()->setDefaultValue(0)->setColumnComment('违章押金');
//            $table->colInt('damagePrice',11)->setIsUnsigned()->setDefaultValue(0)->setColumnComment('车损押金');
//            $table->colVarChar('desc')->setDefaultValue('')->setColumnComment('描述');


        });

        //车辆-图片表
        $sql[]=DDLBuilder::table('info_image',function (Table $table)
        {
            $table->setTableComment('车辆-图片表')->setTableEngine(Engine::INNODB)->setTableCharset(Character::UTF8MB4_GENERAL_CI);
            $table->colInt('id',11)->setIsUnsigned()->setColumnComment('info表的主键');
            $table->colVarChar('image')->setColumnLimit(100)->setDefaultValue('')->setColumnComment('图片地址');
            $table->colTinyInt('level')->setIsUnsigned()->setDefaultValue(0)->setColumnComment('图片权重');
            $table->indexNormal('id_index','id');
        });

        //车辆-标签表
        $sql[]=DDLBuilder::table('info_label',function (Table $table)
        {
            $table->setTableComment('车辆-标签表')->setTableEngine(Engine::INNODB)->setTableCharset(Character::UTF8MB4_GENERAL_CI);
            $table->colInt('id',11)->setIsUnsigned()->setColumnComment('info表的主键');
            $table->colInt('label',11)->setIsUnsigned()->setColumnComment('label表的主键');
            $table->colTinyInt('level')->setIsUnsigned()->setDefaultValue(0)->setColumnComment('标签权重');
            $table->indexNormal('id_index','id');
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

        $this->writeJson(200,'ok','success');

        return true;
    }

    private function carsConfig()
    {
        $redisObj=Redis::defer('local_redis');
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
                'title'=>'酷享自驾','subtext1'=>'你想要的','subtext2'=>'都在这里','image'=>'/static/image/homeModule/1.jpg?v=123','href'=>'/v1/sportsCar',
            ],
            [
                'title'=>'尊享出行','subtext1'=>'专人专车','subtext2'=>'一应俱全','image'=>'/static/image/homeModule/2.jpg?v=123','href'=>'/v1/mpv',
            ],
            [
                'title'=>'极速摩托','subtext1'=>'追求极致','subtext2'=>'畅快淋漓','image'=>'/static/image/homeModule/3.jpg?v=123','href'=>'/v1/motorcycle',
            ],
            [
                'title'=>'安心托管','subtext1'=>'追求极致','subtext2'=>'畅快淋漓','image'=>'/static/image/homeModule/4.jpg?v=123','href'=>'/v1/trusteeship',
            ],
            [
                'title'=>'精致车源','subtext1'=>'炫酷超跑','subtext2'=>'触手可及','image'=>'/static/image/homeModule/5.jpg?v=123','href'=>'/v1/carSource',
            ],
            [
                'title'=>'超值长租','subtext1'=>'长期租赁','subtext2'=>'更多优惠','image'=>'/static/image/homeModule/6.jpg?v=123','href'=>'/v1/rental',
            ],
        ]));

        return true;
    }
}
