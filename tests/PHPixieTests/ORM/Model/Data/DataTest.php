<?php

namespace PHPixieTests\ORM\Model\Data;

/**
 * @coversDefaultClass \PHPixie\ORM\Model\Data\Data
 */
abstract class DataTest extends \PHPixieTests\AbstractORMTest
{
    protected $data;
    protected $originalData;
    
    public function setUp()
    {
        $this->data = $this->getData();
    }
    
    abstract protected function getData();
    
    abstract public function testSetCurrentAsOriginal();
    abstract public function testDiff();
    abstract public function testCurrentData();
    abstract public function testProperties();
}