<?php

namespace PHPixie\ORM\Model;

abstract class Iterator implements \Iterator
{
    public function asArray($modelsAsArrays = false)
    {
        $res = array();
        foreach ($this as $model) {
            if ($modelsAsArrays) {
                $res[] = $model->asArray();
            }else
                $res[] = $model;
        }
    }
}
