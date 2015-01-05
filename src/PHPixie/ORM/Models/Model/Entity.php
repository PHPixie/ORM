<?php

namespace PHPixie\ORM\Models\Model;

interface Entity extends \PHPixie\ORM\Conditions\Condition\In\Item
{
    public function modelName();
    public function asObject($recursive = false);
    public function getRelationshipProperty($relationship, $createMissing = true);
    public function data();
    public function getField($name);
    public function setField($key, $value);
    public function __get($name);
    public function __set($name, $value);
}
