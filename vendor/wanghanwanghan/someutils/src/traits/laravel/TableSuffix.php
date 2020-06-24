<?php

namespace wanghanwanghan\someUtils\traits\laravel;

trait TableSuffix
{
    //laravel中用的orm分表

    private static $suffix;

    //在分表的model中use，执行别的函数之前调用suffix修改EloquentModel后缀
    public static function suffix($suffix)
    {
        static::$suffix = $suffix;
    }

    public function __construct(array $attributes = [])
    {
        $this->table .= static::$suffix;

        parent::__construct($attributes);
    }
}