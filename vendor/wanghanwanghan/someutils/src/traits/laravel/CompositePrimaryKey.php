<?php

namespace wanghanwanghan\someUtils\traits\laravel;

use Illuminate\Database\Eloquent\Builder;

trait CompositePrimaryKey
{
    //laravel orm中的复合主键

    public function getIncrementing()
    {
        return false;
    }

    protected function setKeysForSaveQuery(Builder $query)
    {
        foreach ($this->getKeyName() as $key)
        {
            if ($this->$key)
            {
                $query->where($key, '=', $this->$key);
            }else
            {
                throw new \Exception(__METHOD__ . 'Missing part of the primary key: ' . $key);
            }
        }

        return $query;
    }
}
