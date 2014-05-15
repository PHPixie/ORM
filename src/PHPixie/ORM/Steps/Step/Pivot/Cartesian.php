<?php

namespace PHPixie\ORM\Steps\Step\Pivot;

class Cartesian extends \PHPixie\ORM\Steps\Step
{
    protected $resultStepsMap;
    protected $product;

    public function __construct($resultStepsMap)
    {
        $this->resultStepsMap = $resultStepsMap;
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
        if (empty($this->resultStepsMap))
            return array();
        
        $product = array(array());
        foreach ($this->resultStepsMap as $resultStepMap) {
            $rows = $resultStepMap['resultStep']->getFields($resultStepMap['fields']);
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
            foreach($rows as $row)
                $updatedProduct[] = array_merge($productRow, array_values(get_object_vars($resultItem)));
        return $updatedProduct;
    }

}
