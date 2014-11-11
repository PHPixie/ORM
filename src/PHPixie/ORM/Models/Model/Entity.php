<?php

namespace PHPixie\ORM\Models\Model;

interface Entity extends \PHPixie\ORM\Conditions\Condition\Collection\Item
{
    public function modelName();
    public function asObject($recursive = false);
    public function getRelationshipProperty($relationship, $property);
    public function data();
    public function getField($name);
    public function setField($key, $value);
    public function __get($name);
    public function __set($name, $value);
}
