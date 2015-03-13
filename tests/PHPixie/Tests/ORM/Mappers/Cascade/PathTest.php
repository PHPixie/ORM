<?php

namespace PHPixie\Tests\ORM\Mappers\Cascade;

/**
 * @coversDefaultClass \PHPixie\ORM\Mappers\Cascade\Path
 */
class PathTest extends \PHPixie\Test\Testcase
{
    protected $mappers;
    protected $path;
    
    public function setUp()
    {
        $this->mappers = $this->quickMock('\PHPixie\ORM\Mappers');
        $this->path = $this->path();
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::sides
     * @covers ::addSide
     * @covers ::containsModel
     * @covers ::<protected>
     */
    public function testPath()
    {
        $this->assertSame(array(), $this->path->sides());
        $this->assertSame(false, $this->path->containsModel('pixie'));
        
        $side = $this->side('pixie');
        $this->path->addSide($side);
        $this->assertSame(array($side), $this->path->sides());
        $this->assertSame(true, $this->path->containsModel('pixie'));
    }
    
    /**
     * @covers ::copy
     * @covers ::<protected>
     */
    public function testCopy()
    {
        $this->method($this->mappers, 'cascadePath', $this->path(), array(), 0);
        
        $side = $this->side('pixie');
        $this->path->addSide($side);
        $copy = $this->path->copy();
        
        $this->assertSame($this->path->sides(), $copy->sides());
        $this->assertSame(true, $copy->containsModel('pixie'));
    }
    
    protected function side($modelName)
    {
        $side = $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Side');
        $this->method($side, 'modelName', $modelName, array());
        return $side;
    }
    
    protected function path()
    {
        return new \PHPixie\ORM\Mappers\Cascade\Path($this->mappers);
    }
}