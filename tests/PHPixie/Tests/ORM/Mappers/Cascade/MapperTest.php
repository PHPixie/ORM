<?php

namespace PHPixie\Tests\ORM\Mappers\Cascade;

/**
 * @coversDefaultClass \PHPixie\ORM\Mappers\Cascade\Mapper
 */
abstract class MapperTest extends \PHPixie\Test\Testcase
{
    protected $mappers;
    protected $relationships;
    protected $cascadeMap;
    
    protected $cascadeMapper;
    
    protected $modelName = 'fairy';
    
    public function setUp()
    {
        $this->mappers = $this->quickMock('\PHPixie\ORM\Mappers');
        $this->relationships = $this->quickMock('\PHPixie\ORM\Relationships');
        $this->cascadeMap = $this->cascadeMap();
        
        $this->cascadeMapper = $this->cascadeMapper();
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::isModelHandled
     * @covers ::<protected>
     */
    public function testIsModelHandled()
    {
        foreach(array(true, false) as $isModelHandled) {
            $this->method($this->cascadeMap, 'hasModelSides', $isModelHandled, array('pixie'), 0);
            $this->assertSame($isModelHandled, $this->cascadeMapper->isModelHandled('pixie'));
        }
    }
    
    protected function getReusableResult()
    {
        return $this->abstractMock('\PHPixie\ORM\Steps\Result\Reusable');
    }
    
    protected function getPath()
    {
        return $this->abstractMock('\PHPixie\ORM\Mappers\Cascade\Path');
    }
    
    protected function getRelationship()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship');
    }
    
    protected function getPlan()
    {
        return $this->abstractMock('\PHPixie\ORM\Plans\Plan\Steps');
    }
    
    abstract protected function getSide();
    abstract protected function getHandler();
    abstract protected function cascadeMap();
    abstract protected function cascadeMapper();
}