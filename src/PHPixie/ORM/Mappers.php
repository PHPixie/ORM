<?php

namespace PHPixie\ORM;

class Mappers
{
    protected $ormBuilder;
    
    public function __construct($ormBuilder)
    {
        $this->ormBuilder = $ormBuilder;
    }
    public function cascadePath(){}
    public function cascadeDelete(){}
    public function preload(){}
    public function update(){}
    public function conditions(){}
    public function entity(){}
    public function query(){}
    public function conditionsOptimizer(){}
    public function conditionsNormalizer(){}
}