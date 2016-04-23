<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Side;

class Config extends \PHPixie\ORM\Relationships\Relationship\Implementation\Side\Config
{
    public $model;

    public $parentProperty;
    public $childrenProperty;

    public $allParentsProperty;
    public $allChildrenProperty;

    public $leftKey;
    public $rightKey;
    public $depthKey;
    public $rootIdKey;

    public $onDelete;

    protected function processConfig($configSlice, $inflector)
    {
        $this->model = $configSlice->getRequired('model');

        $this->parentProperty  = $configSlice->get('parentProperty', 'parent');
        $this->childrenProperty = $configSlice->get('childrenProperty', 'children');
        
        $this->leftKey  = $configSlice->get('leftKey', 'left');
        $this->rightKey = $configSlice->get('rightKey', 'right');
        $this->depthKey = $configSlice->get('depthKey', 'depth');
        $this->rootIdKey = $configSlice->get('rootIdKey', 'rootId');

        $this->allParentsProperty = $configSlice->get('allParentsProperty');
        if($this->allParentsProperty === null) {
            $this->allParentsProperty = 'all'.ucfirst($inflector->plural($this->parentProperty));
        }

        $this->allChildrenProperty = $configSlice->get('allChildrenProperty');
        if($this->allChildrenProperty === null) {
            $this->allChildrenProperty = 'all'.ucfirst($this->childrenProperty);
        }

        $this->onDelete = $configSlice->get('onParentDelete', 'moveToTop');
    }
}
