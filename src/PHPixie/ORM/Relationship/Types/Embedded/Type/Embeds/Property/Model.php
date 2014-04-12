<?php

namespace PHPixie\ORM\Relationship\Types\Embedded\Property;

class Embedded extends \PHPixie\ORM\Properties\Property\Model
{
    protected $handler;
    protected $embedConfig;
    
    public function __construct($handler, $side, $model, $embedConfig)
    {
        parent::construct($handler, null, $model);
        $this->embedConfig = $embedConfig;
    }
}