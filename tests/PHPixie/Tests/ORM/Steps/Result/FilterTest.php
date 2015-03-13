<?php

namespace PHPixie\Tests\ORM\Steps\Result;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Result\Filter
 */
class FilterTest extends \PHPixie\Test\Testcase
{
    protected $resultStep;
    protected $fields;
    protected $resultFilter;
    
    public function setUp()
    {
        $this->resultStep = $this->abstractMock('\PHPixie\ORM\Steps\Result');
        $this->fields = array('a', 'b');
        $this->resultFilter = new \PHPixie\ORM\Steps\Result\Filter($this->resultStep, $this->fields);
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::getFirstFieldValues
     */
    public function testGetFirstFieldValues()
    {
        $this->method($this->resultStep, 'getField', array(5), array('a'), 0);
        $this->assertEquals(array(5), $this->resultFilter->getFirstFieldValues());
    }
    
    /**
     * @covers ::getFilteredData
     */
    public function testGetFilteredData()
    {
        $this->method($this->resultStep, 'getFields', array(5), array($this->fields), 0);
        $this->assertEquals(array(5), $this->resultFilter->getFilteredData());
    }
}