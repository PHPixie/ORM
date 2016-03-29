<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Side;

class Config extends \PHPixie\ORM\Relationships\Relationship\Implementation\Side\Config
{
    public $model;
    public $parentProperty;
    public $childrenProperty;

    public $leftKey;
    public $rightKey;
    public $rootIdKey;

    public $onDelete;

    protected function processConfig($configSlice, $inflector)
    {
        $this->model = $configSlice->getRequired('model');

        $this->parentProperty  = $configSlice->get('parentProperty', 'parent');
        $this->childrenProperty = $configSlice->get('childrenProperty', 'children');
        
        $this->leftKey  = $configSlice->get('leftKey', 'left');
        $this->rightKey = $configSlice->get('rightKey', 'right');
        $this->rootIdKey = $configSlice->get('rootIdKey', 'rootId');
        
        $this->onDelete = $configSlice->get('onParentDelete', 'moveToTop');
    }
}
