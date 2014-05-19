<?php

namespace PHPixieTests\ORM\Steps\Step\Pivot;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Pivot\Insert
 */
class InsertTest extends \PHPixieTests\ORM\Steps\Step\Insert\BatchTest
{
    protected $cartesianStep;
    protected $fields = array('a', 'b', 'c', 'd');
    protected $product   = array(
                            array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4),
                            array('a' => 4, 'b' => 2, 'c' => 3, 'd' => 4),
                            array('a' => 8, 'b' => 2, 'c' => 5, 'd' => 4),
                        );
    protected $data;
    
    public function setUp()
    {
        $this->cartesianStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Pivot\Cartesian', array('product'));
        $this->method($this->cartesianStep, 'product', $this->product);
        $this->data = $this->product;
        parent::setUp();
    }
    
    protected function getStep()
    {
        return new \PHPixie\ORM\Steps\Step\Pivot\Insert(
                                            $this->queryPlanner,
                                            $this->insertQuery,
                                            $this->fields,
                                            $this->cartesianStep,
                                            $this->selectQuery
                                        );
    }
}