<?php

namespace PHPixie\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Models
 */
class ModelsTest extends \PHPixie\Test\Testcase
{
    protected $ormBuilder;
    protected $configSlice;
    protected $wrappers;
    
    protected $models;
    
    public function setUp()
    {
        $this->ormBuilder = $this->quickMock('\PHPixie\ORM\Builder');
        $this->configSlice = $this->getConfigSlice();
        $this->wrappers = $this->abstractMock('\PHPixie\ORM\Wrappers');
        
        $this->models = new \PHPixie\ORM\Models($this->ormBuilder, $this->configSlice, $this->wrappers);
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::wrappers
     * @covers ::<protected>
     */
    public function testWrappers()
    {
        $this->assertSame($this->wrappers, $this->models->wrappers());
        
        $models = new \PHPixie\ORM\Models($this->ormBuilder, $this->configSlice);
        $this->assertSame(null, $models->wrappers());
    }
    
    /**
     * @covers ::modelConfigSlice
     * @covers ::<protected>
     */
    public function testModelConfigSlice()
    {
        $slice = $this->getConfigSlice();
        $this->method($this->configSlice, 'slice', $slice, array('pixie'), 0);
        
        $this->assertSame($slice, $this->models->modelConfigSlice('pixie'));
    }
    
    /**
     * @covers ::database
     * @covers ::<protected>
     */
    public function testDatabaseModel()
    {
        $this->modelTest('database', array(
            'configs',
            'database',
            'drivers',
        ));
    }
    
    /**
     * @covers ::embedded
     * @covers ::<protected>
     */
    public function testEmbeddedModel()
    {
        $this->modelTest('embedded', array(
            'configs',
            'data',
            'maps'
        ));
    }
    
    protected function modelTest($method, $dependencyNames)
    {
        $dependencies = array(
            'models' => $this->models
        );
        
        foreach($dependencyNames as $key => $name) {
            $dependencies[$name] = $this->quickMock('\PHPixie\ORM\\'.ucfirst($name));
            $this->method($this->ormBuilder, $name, $dependencies[$name], array(), $key);
        }
        
        $model = $this->models->$method();
        
        foreach($dependencies as $name => $dependency) {
            $this->assertAttributeEquals($dependency, $name, $model);
        }
        
        $this->assertSame($model, $this->models->$method());
    }
    
    protected function getConfigSlice()
    {
        return $this->abstractMock('\PHPixie\Slice\Data');
    }
              
    
}