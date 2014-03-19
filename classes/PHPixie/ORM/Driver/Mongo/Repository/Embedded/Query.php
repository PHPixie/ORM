<?php

namespace PHPixie\ORM\Driver\Mongo\Repository\Embedded;

class Query extends \PHPixie\DB\Driver\Mongo\Query
{
    protected $transform = false;
    
    public function __construct($db, $conditions, $connection, $parser, $config, $type, $fieldPrefix)
    {
        parent::__construct($db, $conditions, $connection, $parser, $config, $type);
        $this->fieldPrefix = $fieldPrefix;
    }

    public function getFields()
    {
        $fields = parent::getFields();
        foreach($fields as $key => $field) {
            $fields[$key] = $this->prefixField($field);
        }
        return $fields;
    }
    
    public function getData() {
        $data = parent::getData();
        $prefixedData = array();
        foreach($data as $key => $value) {
            $prefixedData[$this->fieldPrefix.'.'.$key] => $value;
        }
        return $prefixedData;
    }
    
    public function getType()
    
    
    protected function addCondition($args, $logic = 'and', $negate = false, $builderName = null)
    {
        $this->conditionBuilder($builderName)->addCondition($logic, $negate, $args);

        return $this;
    }
}
