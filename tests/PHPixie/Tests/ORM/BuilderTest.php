<?php

namespace PHPixie\Tests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Builder
 */
class BuilderTest extends \PHPixie\Test\Testcase
{
    protected $database;
    protected $configSlice;
    protected $wrappers;
    
    protected $builder;
    
    public function setUp()
    {
        $this->database    = $this->quickMock('\PHPixie\Database');
        $this->configSlice = $this->getConfigSlice();
        $this->wrappers    = $this->abstractMock('\PHPixie\ORM\Wrappers');
        
        $this->builder = new \PHPixie\ORM\Builder(
            $this->database,
            $this->configSlice,
            $this->wrappers
        );
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::conditions
     * @covers ::configs
     * @covers ::data
     * @covers ::database
     * @covers ::drivers
     * @covers ::loaders
     * @covers ::maps
     * @covers ::mappers
     * @covers ::models
     * @covers ::planners
     * @covers ::plans
     * @covers ::relationships
     * @covers ::repositories
     * @covers ::steps
     * @covers ::values
     * @covers ::<protected>
     */
    public function testInstances()
    {
        $sets = array(
            array('conditions', true),
            array('configs', false),
            array('data', false),
            array('database', false, array(
                'database' => $this->database
            )),
            array('drivers', true),
            array('loaders', true),
            array('maps', true, array(
                'configSlice' => $this->prepareSlice('relationships', 0)
            )),
            array('mappers', true),
            array('models', true, array(
                'configSlice' => $this->prepareSlice('models', 1),
                'wrappers'    => $this->wrappers
            )),
            array('planners', true),
            array('plans', false),
            array('relationships', true),
            array('repositories', false),
            array('steps', true),
            array('values', false)
        );
        
        foreach($sets as $set) {
            $properties = array();
            if($set[1]) {
                $properties['ormBuilder'] = $this->builder;
            }
            
            $method = $set[0];
            $instance = $this->builder->$method();
            $className = '\PHPixie\ORM\\'.ucfirst($method);
            
            $this->assertInstance($instance, $className, $properties);
            $this->assertSame($instance, $this->builder->$method());
        }
    }
    
    protected function prepareSlice($key, $at)
    {
        $slice = $this->getConfigSlice();
        $this->method($this->configSlice, 'slice', $slice, array($key), $at);
        
        return $slice;
    }
    
    protected function getConfigSlice()
    {
        return $this->abstractMock('\PHPixie\Slice\Data');
    }
}