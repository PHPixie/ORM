<?php

namespace PHPixieTests\ORM\Data\Types;

/**
 * @coversDefaultClass \PHPixie\ORM\Data\Types\Map
 */
class MapTest extends \PHPixieTests\ORM\Data\Type\ImplementationTest
{
    protected $data;
    protected $dataBuilder;
    
    public function setUp()
    {
        $this->dataBuilder = $this->quickMock('\PHPixie\ORM\Data');
        $this->data = (object) array(
            'name'    => 'Trixie',
            'flowers' => 5,
        );
        
        parent::setUp();
    }
    
    public function testSetGet()
    {
        $this->assertEquals('Trixie', $this->type->get('name'));
        $this->type->set('test', 5);
        $this->assertEquals(5, $this->type->get('test'));
        $this->setExpectedException('\Exception');
        $this->type->get('test2');
    }
    
    /**
     * @covers ::data
     * @covers ::<protected>
     */
    public function testData()
    {
        $this->assertEquals($this->data, $this->type->data());
        $this->type
                ->set('test', 5)
                ->set('flowers', null);
        $this->assertEquals(array(
                'name'    => 'Trixie',
                'flowers' => null,
                'test'    => 5
            ), (array) $this->type->data());
    }
    
    /**
     * @covers ::originalData
     * @covers ::<protected>
     */
    public function testOriginalData()
    {
        $this->assertEquals($this->data, $this->type->originalData());
        $this->type
                ->set('test', 5)
                ->set('flowers', null);
        $this->assertEquals($this->data, $this->type->originalData());
    }
    
    /**
     * @covers ::setCurrentAsOriginal
     * @covers ::<protected>
     */
    public function testSetCurrentAsOriginal()
    {
        $this->type
                ->set('test', 5)
                ->set('flowers', null);
        $this->type->setCurrentAsOriginal();
        $this->assertEquals((object) array(
            'name'    => 'Trixie',
            'flowers' => null,
            'test'    => 5
        ), $this->type->originalData());
    }
    
    /**
     * @covers ::diff
     * @covers ::<protected>
     */
    public function testDiff()
    {
        $diff = $this->quickMock('\PHPixie\ORM\Data\Diff');
        $this->method($this->dataBuilder, 'diff', $diff, array((object) array()), 0);
        $this->assertEquals($diff, $this->type->diff());
        
        $this->type
                ->set('test', 5)
                ->set('flowers', null);
        
        $this->method($this->dataBuilder, 'diff', $diff, array((object) array(
            'test'    => 5,
            'flowers' => null
        )), 0);
        $this->assertEquals($diff, $this->type->diff());
        $this->type->setCurrentAsOriginal();
        $this->method($this->dataBuilder, 'diff', $diff, array((object) array()), 0);
        $this->assertEquals($diff, $this->type->diff());
    }
    
    
    protected function getType()
    {
        return new \PHPixie\ORM\Data\Types\Map($this->dataBuilder, $this->data);
    }
}