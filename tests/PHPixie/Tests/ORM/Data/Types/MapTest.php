<?php

namespace PHPixie\Tests\ORM\Data\Types;

/**
 * @coversDefaultClass \PHPixie\ORM\Data\Types\Map
 */
class MapTest extends \PHPixie\Tests\ORM\Data\Type\ImplementationTest
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
    
    
    /**
     * @covers ::get
     * @covers ::getRequired
     * @covers ::set
     * @covers ::<protected>
     */
    public function testSetGet()
    {
        $this->assertEquals('Trixie', $this->type->get('name'));
        $this->assertEquals('Trixie', $this->type->getRequired('name'));
        $this->type->set('test', 5);
        $this->assertEquals(5, $this->type->get('test'));
        $this->assertEquals(null, $this->type->get('test2'));
        $this->assertEquals(5, $this->type->get('test2', 5));
    }
    
    /**
     * @covers ::getRequired
     * @covers ::<protected>
     */
    public function testGetRequiredException()
    {
        $this->setExpectedException('\PHPixie\ORM\Exception\Data');
        $this->type->getRequired('tree');
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
        $this->diffTest();
    }
    
    /**
     * @covers ::diff
     * @covers ::originalData
     * @covers ::<protected>
     */
    public function testNull()
    {
        $this->data = null;
        $this->type = $this->getType();
        $this->diffTest();
    }
    
    protected function diffTest()
    {
        $this->assertDiff((object) array());
        
        $this->type
                ->set('test', 5)
                ->set('flowers', null);
        
        $this->assertDiff((object) array(
            'test'    => 5,
            'flowers' => null
        ));
        
        $this->type->setCurrentAsOriginal();
        
        $this->assertDiff((object) array());
    }
    
    protected function assertDiff($set)
    {
        $diff = $this->getDiff();
        
        $this->dataBuilder
            ->expects($this->at(0))
            ->method('diff')
            ->with($set)
            ->will($this->returnValue($diff));
        
        $this->assertEquals($diff, $this->type->diff());
    }
    
    
    protected function getDiff()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Diff');
    }
    protected function getType()
    {
        return new \PHPixie\ORM\Data\Types\Map($this->dataBuilder, $this->data);
    }
}