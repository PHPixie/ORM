<?php

namespace PHPixie\ORM\Steps\Step\Pivot;

class Cartesian extends \PHPixie\ORM\Steps\Step\Query\Insert\Batch\Data
{
    protected $fields;
    protected $resultFilters;

    public function __construct($fields, $resultFilters)
    {
        $this->fields = $fields;
        $this->resultFilters = $resultFilters;
    }

    public function fields()
    {
        return $this->fields;
    }
    
    public function execute()
    {
        $this->data = $this->buildProduct();
    }
    
    protected function buildProduct()
    {
        if (empty($this->resultFilters))
            return array();
        
        $product = array(array());
        foreach ($this->resultFilters as $resultFilter) {
            $rows = $resultFilter->getFilteredData();
            $product = $this->updateProduct($product, $rows);
            if (empty($product))
                break;
        }

        return $product;

    }

    protected function updateProduct($product, $rows)
    {
        $updatedProduct = array();
        foreach($product as $productRow) {
            $productRow = $productRow;
            foreach($rows as $item) {
                $updatedProduct[] = array_merge($productRow, array_values($item));
            }
        }
        return $updatedProduct;
    }

}
