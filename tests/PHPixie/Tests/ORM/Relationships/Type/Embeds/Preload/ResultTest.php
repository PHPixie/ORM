<?php

namespace PHPixie\Tests\ORM\Relationships\Type\Embeds\Preload;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Preload\Result
 */
abstract class ResultTest extends \PHPixie\Test\Testcase
{
    protected $reusableResult;
    protected $embeddedPath;
    
    protected $result;
    
    protected $data;
    protected $iteratorData;

    public function setUp()
    {
        $this->data = array(
            (object) array(
                'name' => 'Pixie',
                'flower' => (object) array(
                    'petals' => (object) array(
                        'color' => 'red'
                    )
                )
            ),

            (object) array(
                'name' => 'Trixie',
                'flower' => (object) array(
                    'petals' => (object) array(
                        'color' => 'green'
                    )
                )
            ),

            (object) array(
                'name' => 'Fairy',
                'flower' => (object) array(

                )
            ),

            (object) array(
                'name' => 'Blum',
            ),

        );
        $this->reusableResult = $this->abstractMock('\PHPixie\ORM\Steps\Result\Reusable');
        $this->result = $this->result();
    }

    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {

    }

    /**
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testGetByOffset()
    {
        $this->prepareData();
        foreach($this->data as $key => $item) {
            $this->assertSame($item, $this->result->getByOffset($key));
        }
        
        $result = $this->result;
        $this->assertException(function() use($result) {
            $result->getByOffset(4);
        }, '\Exception');
        
    }

    /**
     * @covers ::offsetExists
     * @covers ::<protected>
     */
    public function testOffsetExists()
    {
        $this->prepareData();
        $count = count($this->data);
        for($i=0; $i<=$count; $i++) {
            $this->assertEquals($i<$count, $this->result->offsetExists($i));
        }
    }
    
    /**
     * @covers ::getField
     * @covers ::<protected>
     */
    public function testGetField()
    {
        $this->prepareData();
        
        $rows = $this->result->getField('flower.petals.color');
        $this->assertSame(array('red', 'green'), $rows);
        
        $rows = $this->result->getField('flower.petals.color', false);
        $this->assertSame(array('red', 'green', null, null), $rows);
    }
    
    /**
     * @covers ::getFields
     * @covers ::<protected>
     */
    public function testGetFields()
    {
        $this->prepareData();
        $rows = $this->result->getFields(array('name', 'flower.petals'));
        
        $this->assertSame(array(
            array('name' => 'Pixie', 'flower.petals' => $this->data[0]->flower->petals),
            array('name' => 'Trixie', 'flower.petals' => $this->data[1]->flower->petals),
            array('name' => 'Fairy', 'flower.petals' => null),
            array('name' => 'Blum', 'flower.petals' => null),
        ), $rows);
    }
    
    /**
     * @covers ::getIterator
     * @covers ::<protected>
     */
    public function testGetIterator()
    {
        $this->prepareData();
        foreach($this->result as $key => $item) {
            $this->assertSame($this->data[$key], $item);
        }
    }
    
    /**
     * @covers ::asArray
     * @covers ::<protected>
     */
    public function testAsArray()
    {
        $this->prepareData();
        $this->assertSame($this->data, $this->result->asArray());
    }
    
    protected function prepareIterator($data)
    {
        $iterator = new \ArrayIterator($data);
        $this->method($this->reusableResult, 'getIterator', $iterator, array());
    }
    
    protected function prepareData()
    {
        $this->prepareIterator($this->iteratorData);
    }
    
    abstract protected function result();

}
