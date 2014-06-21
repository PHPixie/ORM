<?php

namespace PHPixieTests\ORM\Model\Data;

/**
 * @coversDefaultClass \PHPixie\ORM\Model\Data\Data
 */
abstract class DataTest extends \PHPixieTests\AbstractORMTest
{
    protected $data;
    protected $originalData;
    protected $model;
    
    public function setUp()
    {
        $this->model = $this->quickMock('\PHPixie\ORM\Model');
        $this->data = $this->getData();
    }
    
    /**
     * @covers ::setModel
     * @covers ::<protected>
     */
    public function testSetModel() 
    {
        $this->data->setModel($this->model);
        $this->method($this->model, 'dataProperties', array(), array(), 0);
        $this->data->currentData();
    }
    
    /**
     * @covers ::setCurrentAsOriginal
     * @covers ::<protected>
     */
    public function testSetCurrentAsOriginal() {
        $this->data->setModel($this->model);
        $properties = $this->originalData;
        $properties['test'] = 5;
        $this->assertNotEmpty((array) $this->data->diff());
        $this->data->setCurrentAsOriginal();
        $this->assertEquals(array(), (array) $this->data->diff());
    }
    
    abstract protected function getData();
    abstract public function testDiff();
    abstract public function testCurrentData();
    abstract public function testProperties();
}