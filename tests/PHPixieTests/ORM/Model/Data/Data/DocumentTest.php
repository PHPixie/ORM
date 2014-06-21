<?php

namespace PHPixieTests\ORM\Model\Data\Data;

/**
 * @coversDefaultClass \PHPixie\ORM\Model\Data\Data\Document
 */
class DocumentTest extends \PHPixieTests\ORM\Model\Data\DataTest
{
    protected $documentBuilder;
    
    public function setUp()
    {
        $this->documentBuilder = $this->quickMock('\PHPixie\ORM\Model\Data\Data\Document\Builder');
        $this->originalData = (object) array(
            'name'    => 'Trixie',
            'flowers' => 3,
            'magic'   => (object) array(
                'type'  => 'air',
                'spell' => 'wind'
            ),
            'trees' => array('Oak', 'Pine')
        );
        
        parent::setUp();
    }
    
    protected function getData()
    {
        return new \PHPixie\ORM\Model\Data\Data\Document($this->documentBuilder, $this->originalData);
    }
    
    /**
     * @covers ::diff
     * @covers ::<protected>
     */
    public function testDiff()
    {
        $this->data->setModel($this->model);
        
        $this->method($this->model, 'dataProperties', $this->originalData, array(), 0);
        $this->assertEquals(array(), $this->data->diff());
        
        $this->method($this->model, 'dataProperties', array(
            'magic'   => null,
            'test'    => 5,
            'name'    => 'Trixie',
            'flowers' => 4
        ), array(), 0);
        
        $this->assertEquals(array(
            'magic'   => null,
            'test'    => 5,
            'flowers' => 4
        ), $this->data->diff());
    }
    
    /**
     * @covers ::currentData
     * @covers ::<protected>
     */
    public function testCurrentData()
    {
        $this->assertEquals($this->originalData, (array) $this->data->currentData());
        $this->method($this->model, 'dataProperties', array(
            'magic'   => null,
            'test'    => 5,
            'name'    => 'Trixie',
            'flowers' => 4
        ), array(), 0);
        
        $this->data->setModel($this->model);
        $this->assertEquals(array(
            'magic'   => null,
            'test'    => 5,
            'name'    => 'Trixie',
            'flowers' => 4
        ), (array) $this->data->currentData());

    }
    
    /**
     * @covers ::properties
     * @covers ::<protected>
     */
    public function testProperties()
    {
        $this->assertEquals($this->originalData, $this->data->properties());
        $this->method($this->model, 'dataProperties', array(
            'magic'   => null,
            'test'    => 5,
            'name'    => 'Trixie',
            'flowers' => 4
        ), array(), 0);
        
        $this->data->setModel($this->model);
        $this->assertEquals(array(
            'magic'   => null,
            'test'    => 5,
            'name'    => 'Trixie',
            'flowers' => 4
        ),  $this->data->properties());

    }
    
}