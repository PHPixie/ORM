<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Steps;

class MapQuery extends \PHPixie\ORM\Steps\Step
{
    protected $config;
    protected $type;
    protected $builder;
    protected $result;
    protected $immediateOnly;

    public function __construct($config, $type, $builder, $result, $immediateOnly = false)
    {
        $this->config = $config;
        $this->type = $type;
        $this->builder = $builder;
        $this->result = $result;
        $this->immediateOnly = $immediateOnly;
    }

    public function execute()
    {
        $fields = array(
            $rootIdKey = $this->config->rootIdKey,
            $leftKey = $this->config->leftKey,
            $rightKey = $this->config->rightKey
        );

        $depthKey = $this->config->depthKey;

        if($this->immediateOnly) {
            $fields[]= $depthKey;
        }

        $data = $this->result->getFields($fields);
        if(empty($data)) {
            $this->builder->_and($leftKey, '>*', $rightKey);
            return;
        }
        
        foreach($data as $row) {
            $this->builder->startOrGroup();
            $this->builder->_and($rootIdKey, $row[$rootIdKey]);

            if ($this->type == 'parent') {
                $depthModifier = -1;
                $this->builder
                    ->_and($leftKey, '<', $row[$leftKey])
                    ->_and($rightKey, '>', $row[$rightKey]);
            } else {
                $depthModifier = 1;
                $this->builder
                    ->_and($leftKey, '>', $row[$leftKey])
                    ->_and($rightKey, '<', $row[$rightKey]);
            }

            if($this->immediateOnly) {
                $this->builder->_and($depthKey, $row[$depthKey] + $depthModifier);
            }

            $this->builder->endGroup();
        }
    }

    public function usedConnections()
    {
        return array();
    }
}
