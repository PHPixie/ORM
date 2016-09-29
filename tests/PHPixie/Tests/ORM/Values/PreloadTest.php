<?php

namespace PHPixie\Tests\ORM\Values;

/**
 * @coversDefaultClass \PHPixie\ORM\Values\Preload
 */
class PreloadTest extends \PHPixie\Test\Testcase
{
    protected $values;
    protected $preload;
    
    public function setUp()
    {
        $this->values = $this->quickMock('\PHPixie\ORM\Values');
        $this->preload = $this->preload();
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::getProperty
     * @covers ::addProperty
     * @covers ::properties
     * @covers ::<protected>
     */
    public function testProperties()
    {
        $this->assertSame(array(), $this->preload->properties());
        $this->assertEquals(null, $this->preload->getProperty('fairy'));
        
        $property = $this->getCascadingProperty('fairy');
        $this->assertSame($this->preload, $this->preload->addProperty($property));
        
        $this->assertSame(array($property), $this->preload->properties());
        $this->assertSame($property, $this->preload->getProperty('fairy'));
    }
    
    /**
     * @covers ::addExplodedPath
     * @covers ::<protected>
     */
    public function testAddExplodedPath()
    {
        $path = array('fairy', 'pixie', 'trixie');
        $fairy = $this->prepareAddTest($path);
        $this->assertSame($this->preload, $this->preload->addExplodedPath($path));
        
        $path = array('fairy', 'trixie');
        $this->prepareAddTest($path, $fairy);
        $this->assertSame($this->preload, $this->preload->addExplodedPath($path));
        
        $path = array('fairy');
        $this->prepareAddTest($path, $fairy);
        $this->assertSame($this->preload, $this->preload->addExplodedPath($path));
    }
    
    /**
     * @covers ::addPath
     * @covers ::<protected>
     */
    public function testAddPath()
    {
        $path = array('fairy', 'pixie', 'trixie');
        $fairy = $this->prepareAddTest($path);
        $this->assertSame($this->preload, $this->preload->addPath(implode('.', $path)));
    }
    
    public function testAdd()
    {
        $path = array('fairy', 'pixie', 'trixie');
        $fairy = $this->prepareAddTest($path);
        $this->assertSame($this->preload, $this->preload->add(implode('.', $path)));
        
        $property = $this->getCascadingProperty('fairy');
        $this->assertSame($this->preload, $this->preload->addProperty($property));
        $this->assertSame(array($property), $this->preload->properties());
        
        $this->setExpectedException('\PHPixie\ORM\Exception\Query');
        $this->preload->add(5);
    }
    
    protected function prepareAddTest($path, $property = null)
    {
        $name = array_shift($path);
        
        if($property === null) {
            $property = $this->getCascadingProperty($name);
            $this->method($this->values, 'cascadingPreloadProperty', $property, array($name, array()), 0);
        }
        
        if(!empty($path)) {
            $this->method($property->preload(), 'addExplodedPath', null, array($path, array()), 0);
        }
        
        return $property;
    }
    
    protected function getCascadingProperty($name)
    {
        $property = $this->quickMock('\PHPixie\ORM\Values\Preload\Property\Cascading');
        $this->method($property, 'propertyName', $name, array());
        
        $preload = $this->quickMock('\PHPixie\ORM\Values\Preload');
        $this->method($property, 'preload', $preload, array());
        
        return $property;
    }
    
    protected function preload()
    {
        return new \PHPixie\ORM\Values\Preload($this->values);
    }

}