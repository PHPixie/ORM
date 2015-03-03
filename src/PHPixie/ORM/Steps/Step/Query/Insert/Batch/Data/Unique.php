<?php

namespace PHPixie\ORM\Steps\Step\Query\Insert\Batch\Data;

class Unique extends \PHPixie\ORM\Steps\Step\Query\Insert\Batch\Data
{
    protected $dataStep;
    protected $selectQuery;
    
    public function __construct($dataStep, $selectQuery)
    {
        $this->dataStep = $dataStep;
        $this->selectQuery = $selectQuery;
    }
    
    public function fields()
    {
        return $this->dataStep->fields();
    }
    
    public function execute()
    {
        $fields = $this->fields();
        $data = $this->dataStep->data();
        $this->selectQuery->fields($fields);
        
        foreach($fields as $key => $field) {
            $values = array();
            foreach($data as $dataRow){
                $values[]=$dataRow[$key];
            }
            $this->selectQuery->addInOperatorCondition($field, $values);
        }

        $existingItems = $this->selectQuery->execute()->getFields($fields);
        
        $existing = array();
        foreach($existingItems as $existingItem) {
            $existingValues = array();
            foreach($fields as $key => $field)
                $existingValues[$key]=$existingItem[$field];
            $existing[]= $existingValues;
        }
        
        $filteredProduct = array();
        foreach($data as $dataRow) {
            if(!in_array($dataRow, $existing, true)) {
                $filteredProduct[] = $dataRow;
            }
        }
        
        $this->data = $filteredProduct;
    }
    
    public function usedConnections()
    {
        return array($this->selectQuery->connection());
    }
}