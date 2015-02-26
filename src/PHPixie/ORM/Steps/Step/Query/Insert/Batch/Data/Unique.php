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
        $product = $this->dataStep->data();
        $this->selectQuery->fields($fields);
        
        foreach($fields as $key => $field) {
            $values = array();
            foreach($product as $productRow){
                $values[]=$productRow[$key];
            }
            $this->selectQuery->where($field, 'in', $values);
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
        foreach($product as $productRow)
            if(!in_array($productRow, $existing))
                $filteredProduct[] = $productRow;
        
        $this->data = $filteredProduct;
    }
    
    public function usedConnections()
    {
        return array($this->selectQuery->connection());
    }
}