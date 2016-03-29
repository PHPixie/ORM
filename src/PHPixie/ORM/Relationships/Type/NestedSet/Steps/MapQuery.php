<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Steps;

class MapQuery
{
    protected $side;
    protected $builder;
    protected $resultStep;
    protected $immediateOnly;

    public function __construct($side, $builder, $resultStep, $immediateOnly = false)
    {
        $this->side = $side;
        $this->builder = $builder;
        $this->resultStep = $resultStep;
        $this->immediateOnly = $immediateOnly;
    }

    public function execute()
    {
        $type = $this->side->type();

        $fields = ['rootId', 'left', 'right'];
        if($this->immediateOnly) {
            $fields[]= 'depth';
        }

        $data = $this->resultStep->getFields($fields);
        foreach($data as $row) {
            $this->builder->startOrGroup();
            $this->builder->_and('rootId', $row['rootId']);

            if ($type == 'parent') {
                $depthModifier = -1;
                $this->builder
                    ->_and('left', '<', $row['left'])
                    ->_and('right', '>', $row['right']);
            } else {
                $depthModifier = 1;
                $this->builder
                    ->_and('left', '>', $row['left'])
                    ->_and('right', '<', $row['right']);
            }

            if($this->immediateOnly) {
                $this->builder->_and('depth', $row['depth'] + $depthModifier);
            }

            $this->builder->endGroup();
        }
    }

    public function usedConnections()
    {
        return array();
    }
}
