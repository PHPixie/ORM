<?php

namespace PHPixieTests\ORM\Mappers\Cascade;

/**
 * @coversDefaultClass \PHPixie\ORM\Mappers\Cascade\Mapper
 */
abstract class MapperTest extends \PHPixieTests\AbstractORMTest
{
    protected $mappers;
    protected $maps;
    protected $relationships;
    
    protected $cascadeMapper;
    
    protected $modelName = 'fairy';
    
    public function setUp()
    {
        $this->mappers = $this->quickMock('\PHPixie\ORM\Mappers');
        $this->maps = $this->quickMock('\PHPixie\ORM\Maps');
        $this->relationships = $this->quickMock('\PHPixie\ORM\Relationships');
        
        $this->cascadeMapper = $this->cascadeMapper();
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
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