<?php

namespace wanghanwanghan\someUtils\utils;

class file
{
    //删除文件夹下$n分钟前创建的文件
    public static function delFileByCtime($dir,$n='')
    {
        if (is_dir($dir) && !empty($n))
        {
            if ($dh = opendir($dir))
            {
                while (false !== ($file = readdir($dh)))
                {
                    if ($file != "." && $file != "..")
                    {
                        $fullpath = $dir . DIRECTORY_SEPARATOR . $file;

                        if (is_dir($fullpath))
                        {
                            if (count(scandir($fullpath)) == 2 && $file != date('Y-m-d'))
                            {
                                //目录为空,=2是因为.和..存在
                                rmdir($fullpath); // 删除空目录
                            }else
                            {
                                self::delFileByCtime($fullpath,$n); //不为空继续判断文件夹中文件
                            }

                        }else
                        {
                            $filedate = filemtime($fullpath); //获取文件创建时间
                            $minutes = round((time() - $filedate) / 60); //计算已创建分钟
                            if ($minutes > $n) unlink($fullpath); //删除过期文件
                        }
                    }
                }
            }
            closedir($dh);
        }

        return true;
    }



}
