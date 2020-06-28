<?php

namespace App\HttpController\Business\Page;

use App\HttpController\Business\BusinessBase;
use EasySwoole\DDL\Blueprint\Table;
use EasySwoole\DDL\DDLBuilder;
use EasySwoole\DDL\Enum\Character;
use EasySwoole\DDL\Enum\Engine;
use EasySwoole\Pool\Manager;
use EasySwoole\RedisPool\Redis;
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
        $this->createTable();


        //获取一个上传文件,返回的是一个\EasySwoole\Http\Message\UploadFile的对象
        $file=$this->request()->getUploadedFile('img');
        $data=$this->request()->getUploadedFiles();

        $this->writeJson(200,['uuid'=>control::getUuid()],'小超垃圾');

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
            $table->colVarChar('phone',11)->setIsNotNull()->setColumnComment('手机号');
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
            $table->colInt('forfeitPrice',11)->setIsUnsigned()->setDefaultValue(0)->setColumnComment('违章押金');
            $table->colInt('damagePrice',11)->setIsUnsigned()->setDefaultValue(0)->setColumnComment('车损押金');
            $table->colVarChar('desc',255)->setDefaultValue('')->setColumnComment('描述');
            $table->colVarChar('license',50)->setDefaultValue('')->setColumnComment('车牌照');
            $table->colInt('level',11)->setIsUnsigned()->setDefaultValue(0)->setColumnComment('权重');
            $table->colInt('hot',11)->setIsUnsigned()->setDefaultValue(0)->setColumnComment('热度');
            $table->colVarChar('city')->setColumnLimit(50)->setDefaultValue('北京')->setColumnComment('所属城市');




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
