<?php

namespace PHPixie\ORM\Steps;

interface Result extends \IteratorAggregate
{
    public function getField($field, $skipNulls = true);
    public function getFields($fields);
    public function asArray();
}