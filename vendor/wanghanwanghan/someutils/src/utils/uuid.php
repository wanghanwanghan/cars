<?php

namespace wanghanwanghan\someUtils\utils;

class uuid
{
    //随机uuid
    public static function getUuid($limit=32)
    {
        mt_srand();

        return $limit >= 32 ? md5(uniqid(mt_rand(),true)) : substr(md5(uniqid(mt_rand(),true)),0,$limit);
    }



}
