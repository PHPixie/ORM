<?php

namespace PHPixie\ORM\Steps\Step\Pivot;

class Cartesian extends \PHPixie\ORM\Steps\Step
{
    protected $resultFilters;
    protected $product;

    public function __construct($resultFilters)
    {
        $this->resultFilters = $resultFilters;
    }

    public function execute()
    {
        $this->product = $this->buildProduct();
    }

    public function product()
    {
        if ($this->product === null)
            throw new \PHPixie\ORM\Exception\Plan("This plan step has not been executed yet.");

        return $this->product;
    }

    protected function buildProduct()
    {
        if (empty($this->resultFilters))
            return array();
        
        $product = array(array());
        var_dump($this->resultFilters);
        foreach ($this->resultFilters as $resultFilter) {
            $rows = $resultFilter->getFilteredData();
            var_dump($rows);
            var_dump(2222);
            $product = $this->updateProduct($product, $rows);
            if (empty($product))
                break;
        }

        return $product;

    }

    protected function updateProduct($product, $rows)
    {
        $updatedProduct = array();
        foreach($product as $productRow)
            foreach($rows as $item)
                $updatedProduct[] = array_merge($productRow, array_values($item));
        return $updatedProduct;
    }

}
