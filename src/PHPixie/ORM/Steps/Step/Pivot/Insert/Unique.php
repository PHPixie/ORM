<?php

namespace PHPixie\ORM\Steps\Step\Pivot\Insert;

class Unique extends \PHPixie\ORM\Steps\Step\Pivot\Insert
{
    protected $selectQuery;
    
    public function __construct($insertQuery, $queryPlanner, $fields, $cartesianStep, $selectQuery)
    {
        parent::__construct($insertQuery, $queryPlanner, $fields, $cartesianStep);
        $this->selectQuery = $selectQuery;
    }
    
    protected function prepareBatchData()
    {
        $product = $this->cartesianStep->product();
        $this->selectQuery->fields($this->fields);
        
        foreach($this->fields as $key => $field) {
            $values = array();
            foreach($product as $productRow){
                $values[]=$productRow[$field];
            }
            $this->selectQuery->where($field, 'in', $values);
        }

        $existingItems = $this->selectQuery->execute()->getFields($this->fields);
        
        $existing = array();
        foreach($existingItems as $existingItem) {
            $existingValues = array();
            foreach($this->fields as $field)
                $existingValues[$field]=$existingItem[$field];
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
