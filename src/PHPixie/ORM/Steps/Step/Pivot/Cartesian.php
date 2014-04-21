<?php

namespace PHPixie\ORM\Steps\Step\Pivot;

class Cartesian extends \PHPixie\ORM\Steps\Step
{
    protected $resultSteps;
    protected $product;

    public function __construct($resultSteps)
    {
        $this->resultSteps = $resultSteps;
    }

    public function execute()
    {
        $this->product = $this->buildProduct();
    }

    public function product()
    {
        if ($this->product === null)
            throw new \PHPixie\Exception\Step("This plan step has not been executed yet.")

        return $this->product;
    }

    protected function buildProduct()
    {
        if (empty($this->resultSteps))
            return array();

        $product = array(array());
        foreach ($this->resultSteps as $resultStep) {
            $product = $this->updateProduct($product, $resultStep->result());
            if (empty($product))
                break;
        }

        return $product;

    }

    protected function updateProduct($product, $result)
    {
        $updatedProduct = array();
        foreach($product as $productRow)
            foreach($result as $resultItem)
                $updatedProduct = array_merge($productRow, array_values(get_object_vars($resultItem)));

        return $updatedProduct;
    }

}
