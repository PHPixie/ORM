<?php

namespace PHPixie\ORM\Storage;

interface Entity
{
    public function modelName();
    public function asObject($recursive = false);
    public function getRelationshipProperty($relationship, $property);
    public function data();
    public function getField($name);
    public function __get($name);
    public function __set($name, $value)
}
