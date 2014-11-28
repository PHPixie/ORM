<?php

namespace PHPixie\ORM;

class Values
{
    public function orderBy($field, $direction)
    {
        return new \PHPixie\ORM\Values\OrderBy($field, $direction);
    }
    
    public function preloadProperty(){}
    public function preload(){}
    public function update(){}
    public function updateIncrement(){}
    public function updateRemove(){}
}