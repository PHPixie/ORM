<?php

namespace PHPixie\ORM\Relationships\Type\ManyToMany\Side;

class Config extends \PHPixie\ORM\Relationships\Relationship\Implementation\Side\Config
{
    public $leftModel;
    public $leftProperty;
    public $leftPivotKey;

    public $rightModel;
    public $rightProperty;
    public $rightPivotKey;

    public $pivot;
    public $pivotConnection;

    protected function processConfig($configSlice, $inflector)
    {
        $sides = array('left' => 'right', 'right' => 'left');

        foreach ($sides as $side) {
            $property = $side.'Model';
            $this->$property = $configSlice->getRequired($side);
        }

        foreach ($sides as $side => $opposing) {
            $property = $side.'Property';
            if(($this->$property = $configSlice->get($side.'Options.property', null)) === null)
                $this->$property = $inflector->plural($this->get($opposing.'Model'));
        }

        $this->pivot = $configSlice->get('pivot', null);

        if($this->pivot === null) {
            $this->pivot = $this->rightProperty.ucfirst($this->leftProperty);
        }

        $this->pivotConnection = $configSlice->get('pivotOptions.connection', null);

        foreach ($sides as $side => $opposing) {
            $property = $side.'PivotKey';
            if(($this->$property = $configSlice->get('pivotOptions.'.$side.'Key', null)) === null)
                $this->$property = $inflector->singular($this->get($opposing.'Property')).'Id';
        }
    }
}
