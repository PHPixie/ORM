<?php

namespace PHPixie\ORM\Relationships\Type\ManyToMany\Side;

class Config extends \PHPixie\ORM\Relationships\Relationship\Implementation\Side\Config
{
    public $model;
    public $parentsProperty;
    public $childrenProperty;

    public $leftKey;
    public $rightKey;
    
    public $onDelete;

    protected function processConfig($configSlice, $inflector)
    {
        $this->model = $configSlice->getRequired('model');

        $this->parentsProperty  = $configSlice->get('parentsProperty', 'parents');
        $this->childrenProperty = $configSlice->get('childrenProperty', 'children');
        
        $this->leftKey  = $configSlice->get('leftKey', 'left');
        $this->rightKey = $configSlice->get('rightKey', 'right');
        
        $this->onDelete = $configSlice->get('onParentDelete', 'moveToTop');
    }
}
