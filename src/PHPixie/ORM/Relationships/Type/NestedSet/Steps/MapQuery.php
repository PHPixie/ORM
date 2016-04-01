<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Steps;

class MapQuery
{
    protected $config;
    protected $type;
    protected $builder;
    protected $resultStep;
    protected $immediateOnly;

    public function __construct($config, $type, $builder, $resultStep, $immediateOnly = false)
    {
        var_dump($immediateOnly);
        $this->config = $config;
        $this->type = $type;
        $this->builder = $builder;
        $this->resultStep = $resultStep;
        $this->immediateOnly = $immediateOnly;
    }

    public function execute()
    {
        $fields = ['rootId', 'left', 'right'];
        if($this->immediateOnly) {
            $fields[]= 'depth';
        }

        $data = $this->resultStep->getFields($fields);
        foreach($data as $row) {
            $this->builder->startOrGroup();
            $this->builder->_and('rootId', $row['rootId']);

            if ($this->type == 'parent') {
                $depthModifier = 1;
                $this->builder
                    ->_and('left', '<', $row['left'])
                    ->_and('right', '>', $row['right']);
            } else {
                $depthModifier = -1;
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
