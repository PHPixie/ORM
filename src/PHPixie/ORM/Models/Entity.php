<?php

namespace PHPixie\ORM\Models;

interface Entity
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
