<?php

namespace PHPixieTests\ORM\Relationships\Type\Embeds\Preload;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Preload\Result
 */
abstract class ResultTest extends \PHPixieTests\AbstractORMTest
{
    protected $reusableResult;
    protected $embeddedPath;
    
    protected $result;
    
    protected $data;

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
                'name' => 'fairy',
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
        
        $this->setExpectedException('\Exception');
        $this->result->getByOffset(count($this->data));
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
    
    protected function prepareIterator($data)
    {
        $iterator = new \ArrayIterator($data);
        $this->method($this->reusableResult, 'getIterator', $iterator, array());
    }
    
    abstract protected function prepareData();
    abstract protected function result();

}
