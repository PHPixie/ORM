<?php

namespace PHPixie\ORM\Relationships\Types\ManyToMany\Side;

class Config extends \PHPixie\ORM\Relationships\Relationship\Side\Config
{
    public $leftModel;
    public $leftProperty;
    public $leftPivotKey;

    public $rightModel;
    public $rightProperty;
    public $rightPivotKey;

    public $pivot;
    public $pivotConnection;

    protected function processConfig($config, $inflector)
    {
        $sides = array('left' => 'right', 'right' => 'left');

        foreach ($sides as $side) {
            $property = $side.'Model';
            $this->$property = $config->get($side);
        }

        foreach ($sides as $side => $opposing) {
            $property = $side.'Property';
            if(($this->$property = $config->get($side.'Options.property', null)) === null)
                $this->$property = $inflector->plural($this->get($opposing.'Model'));
        }

        $this->pivot = $config->get('pivot', null);

        if($this->pivot === null) {
            $this->pivot = $this->rightProperty.'_'.$this->leftProperty;
        }

        $this->pivotConnection = $config->get('pivotOptions.connection', null);

        foreach ($sides as $side => $opposing) {
            $property = $side.'PivotKey';
            if(($this->$property = $config->get('pivotOptions.'.$side.'Key', null)) === null)
                $this->$property = $inflector->singular($this->get($opposing.'Property')).'_id';
        }
    }
}
