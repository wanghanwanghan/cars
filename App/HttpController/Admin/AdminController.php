<?php

namespace App\HttpController\Admin;

use App\HttpController\Index;
use EasySwoole\DDL\Blueprint\Table;
use EasySwoole\DDL\DDLBuilder;
use EasySwoole\DDL\Enum\Character;
use EasySwoole\DDL\Enum\Engine;
use EasySwoole\Pool\Manager;
use wanghanwanghan\someUtils\control;
use function Couchbase\defaultDecoder;

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
        $this->createTable();

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

            if ($one->moveTo($path))
            {
                $code=200;
                $msg=null;
            }else
            {
                $code=201;
                $msg='保存图片失败';
            }
        }

        $this->writeJson($code,$filename,$msg);

        return true;
    }

    //录入车辆信息
    public function createSportsCar()
    {
        $method=$this->request()->getMethod();

        $obj=Manager::getInstance()->get('cars')->getObj();

        //get是拿页面要展示的信息
        if ($method==='GET')
        {
            $obj->queryBuilder()->get('carType');
            $carType=$obj->execBuilder();

            $obj->queryBuilder()->get('carBrand');
            $carBrand=$obj->execBuilder();

            $obj->queryBuilder()->get('carLicenseType');
            $carLicenseType=$obj->execBuilder();

            $obj->queryBuilder()->get('china_area');
            $china_area=$obj->execBuilder();
            $tmp=[];
            control::traverseMenu($china_area,$tmp);
            $china_area=$tmp;

            $obj->queryBuilder()->get('carBelong');
            $carBelong=$obj->execBuilder();

            $this->writeJson(200,[
                'carType'=>$carType,
                'carBrand'=>$carBrand,
                'carLicenseType'=>$carLicenseType,
                'china_area'=>$china_area,
                'carBelong'=>$carBelong,
            ]);

        }else
        {
            //post是录入车辆信息
            $data=[
                'carType'=>$this->request()->getRequestParam('carType') ?? 1,//车辆类型
                'carBrand'=>$this->request()->getRequestParam('carBrand') ?? 1,//品牌
                'carModel'=>$this->request()->getRequestParam('carModel') ?? '无',//型号
                'engine'=>$this->request()->getRequestParam('engine') ?? 1.0,//排量
                'year'=>$this->request()->getRequestParam('year') ?? 2020,//年份
                'carLicenseType'=>$this->request()->getRequestParam('carLicenseType') ?? 1,//牌照
                'carBelongCity'=>$this->request()->getRequestParam('carBelongCity') ?? 1,//所属城市
                'operateType'=>$this->request()->getRequestParam('carBelongCity') ?? '自动挡',//操作模式
                'seatNum'=>$this->request()->getRequestParam('seatNum') ?? 2,//座位个数
                'driveType'=>$this->request()->getRequestParam('driveType') ?? '四驱',//驱动方式
                'isRoadster'=>$this->request()->getRequestParam('isRoadster') ?? '否',//是否敞
                'carColor'=>$this->request()->getRequestParam('carColor') ?? '钻石白',//外观颜色
                'insideColor'=>$this->request()->getRequestParam('insideColor') ?? '尊贵棕',//内饰颜色
                'dayPrice'=>$this->request()->getRequestParam('dayPrice') ?? 5000,//日租价格
                'dayDiscount'=>$this->request()->getRequestParam('dayDiscount') ?? 10,//日租折扣
                'goPrice'=>$this->request()->getRequestParam('goPrice') ?? 3000,//出行价格
                'goDiscount'=>$this->request()->getRequestParam('goDiscount') ?? 10,//出行折扣
                'kilPrice'=>$this->request()->getRequestParam('kilPrice') ?? 20.0,//每公里价格
                'carNun'=>$this->request()->getRequestParam('carNun') ?? 20,//库存剩余
                'carBelong'=>$this->request()->getRequestParam('carBelong') ?? 1,//所属车行
                'damagePrice'=>$this->request()->getRequestParam('damagePrice') ?? 20000,//车损押金
                'forfeitPrice'=>$this->request()->getRequestParam('forfeitPrice') ?? 2000,//违章押金
                'isActivities'=>$this->request()->getRequestParam('isActivities') ?? '否',//是否参加活动
                'rentMin'=>$this->request()->getRequestParam('rentMin') ?? 1,//最小天数
                'rentMax'=>$this->request()->getRequestParam('rentMax') ?? 9999,//最大天数
            ];

            $obj->queryBuilder()->insert('carInfo',$data);

            $res=$obj->execBuilder();

            if ($res==true)
            {
                $insertId=$obj->mysqlClient()->insert_id;

                $images=$this->request()->getRequestParam('images');//图片地址








                $code=200;
                $msg=null;
            }else
            {
                $code=201;
                $msg='数据入库错误';
            }

            $this->writeJson($code,[$images],$msg);
        }

        Manager::getInstance()->get('cars')->recycleObj($obj);
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

        //车行信息表
        $sql[]=DDLBuilder::table('carBelong',function (Table $table)
        {
            $table->setTableComment('车行信息表')->setTableEngine(Engine::INNODB)->setTableCharset(Character::UTF8MB4_GENERAL_CI);
            $table->colInt('id',11)->setColumnComment('主键')->setIsAutoIncrement()->setIsUnsigned()->setIsPrimaryKey();
            $table->colVarChar('name')->setColumnLimit(100)->setColumnComment('车行名称');
            $table->colVarChar('lng')->setColumnLimit(50)->setColumnComment('经纬度');
            $table->colVarChar('lat')->setColumnLimit(50)->setColumnComment('经纬度');
            $table->colVarChar('geo')->setColumnLimit(50)->setColumnComment('geo');
            $table->colVarChar('address')->setColumnLimit(255)->setColumnComment('地址');
            $table->colVarChar('tel')->setColumnLimit(50)->setColumnComment('座机');
            $table->colVarChar('phone')->setColumnLimit(50)->setColumnComment('手机');
            $table->colVarChar('open')->setColumnLimit(50)->setColumnComment('开门时间');
            $table->colVarChar('close')->setColumnLimit(50)->setColumnComment('打烊时间');
        });

        //车辆信息表
        $sql[]=DDLBuilder::table('carInfo',function (Table $table)
        {
            $table->setTableComment('车辆信息表')->setTableEngine(Engine::INNODB)->setTableCharset(Character::UTF8MB4_GENERAL_CI);
            $table->colInt('id',11)->setIsAutoIncrement()->setIsUnsigned()->setColumnComment('主键')->setIsPrimaryKey();
            $table->colInt('carType')->setIsUnsigned()->setColumnComment('车辆类型表id');
            $table->colInt('carBrand')->setIsUnsigned()->setColumnComment('车辆品牌表id');
            $table->colVarChar('carModel')->setColumnLimit(50)->setColumnComment('车辆型号');
            $table->colDecimal('engine',5,2)->setIsUnsigned()->setColumnComment('排量');
            $table->colInt('year')->setIsUnsigned()->setColumnComment('年份');
            $table->colInt('carLicenseType')->setIsUnsigned()->setColumnComment('车辆牌照类型表id');
            $table->colInt('carBelongCity')->setIsUnsigned()->setColumnComment('城市表id');
            $table->colVarChar('operateType')->setColumnLimit(50)->setColumnComment('操作模式');
            $table->colTinyInt('seatNum')->setIsUnsigned()->setColumnComment('座椅个数');
            $table->colVarChar('driveType')->setColumnLimit(50)->setColumnComment('驱动模式');
            $table->colVarChar('isRoadster')->setColumnLimit(50)->setColumnComment('是否敞篷');
            $table->colVarChar('carColor')->setColumnLimit(50)->setColumnComment('外观颜色');
            $table->colVarChar('insideColor')->setColumnLimit(50)->setColumnComment('内饰颜色');
            $table->colInt('dayPrice')->setIsUnsigned()->setColumnComment('日租价格');
            $table->colInt('dayDiscount')->setIsUnsigned()->setColumnComment('日租折扣');
            $table->colInt('goPrice')->setIsUnsigned()->setColumnComment('出行价格');
            $table->colInt('goDiscount')->setIsUnsigned()->setColumnComment('出行折扣');
            $table->colDecimal('kilPrice',5,2)->setIsUnsigned()->setColumnComment('每公里价格');
            $table->colInt('carNun')->setIsUnsigned()->setColumnComment('车辆数量');
            $table->colInt('carBelong')->setIsUnsigned()->setColumnComment('车行信息表id');
            $table->colInt('damagePrice')->setIsUnsigned()->setColumnComment('车损押金');
            $table->colInt('forfeitPrice')->setIsUnsigned()->setColumnComment('违章押金');
            $table->colVarChar('isActivities')->setColumnLimit(50)->setColumnComment('是否参与活动');
            $table->colInt('rentMin')->setIsUnsigned()->setColumnComment('最短天数');
            $table->colInt('rentMax')->setIsUnsigned()->setColumnComment('最长天数');
        });

        //车辆图片表
        $sql[]=DDLBuilder::table('carImage',function (Table $table)
        {
            $table->setTableComment('车辆图片表')->setTableEngine(Engine::INNODB)->setTableCharset(Character::UTF8MB4_GENERAL_CI);
            $table->colInt('id',11)->setIsAutoIncrement()->setIsUnsigned()->setColumnComment('主键')->setIsPrimaryKey();
            $table->colInt('cid')->setIsUnsigned()->setColumnComment('车辆主键');
            $table->colVarChar('imageUrl')->setColumnLimit(255)->setColumnComment('图片地址');
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
