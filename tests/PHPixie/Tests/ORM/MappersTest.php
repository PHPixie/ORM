<?php

namespace PHPixie\Tests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Mappers
 */
class MappersTest extends \PHPixie\Test\Testcase
{
    protected $ormBuilder;
    
    protected $mappers;
    
    protected $dependencies;
    
    public function setUp()
    {
        $this->ormBuilder = $this->quickMock('\PHPixie\ORM\Builder');
        $this->mappers = new \PHPixie\ORM\Mappers($this->ormBuilder);
        
        $dependencyNames = array(
            'conditions',
            'loaders',
            'maps',
            'models',
            'planners',
            'plans',
            'relationships',
            'steps'
        );
        
        foreach($dependencyNames as $name) {
            $dependency = $this->quickMock('\PHPixie\ORM\\'.ucfirst($name));
            $this->method($this->ormBuilder, $name, $dependency, array());
            $this->dependencies[$name] = $dependency;
        }
        
        $this->method($this->ormBuilder, 'mappers', $this->mappers, array());
        $this->dependencies['mappers'] = $this->mappers;
        
        $maps = array(
            'relationship'  => $this->quickMock('\PHPixie\ORM\Maps\Map\Relationship'),
            'preload'       => $this->quickMock('\PHPixie\ORM\Maps\Map\Preload'),
            'cascadeDelete' => $this->quickMock('\PHPixie\ORM\Maps\Map\Cascade\Delete'),
        );
        
        foreach($maps as $name => $map) {
            $this->method($this->dependencies['maps'], $name, $map, array());
            $this->dependencies[$name.'Map'] = $map;
        }
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::query
     * @covers ::<protected>
     */
    public function testQuery()
    {
        $this->assertMapper('query', 'Query', array(
            'mappers',
            'loaders',
            'models',
            'plans',
            'steps'
        ));
    }
    
    /**
     * @covers ::conditions
     * @covers ::<protected>
     */
    public function testConditions()
    {
        $this->assertMapper('conditions', 'Conditions', array(
            'mappers',
            'planners',
            'relationshipMap'
        ));
    }
    
    /**
     * @covers ::conditionsOptimizer
     * @covers ::<protected>
     */
    public function testConditionsOptimizer()
    {
        $this->assertMapper('conditionsOptimizer', 'Conditions\Optimizer', array(
            'mappers',
            'conditions'
        ));
    }
    
    /**
     * @covers ::conditionsNormalizer
     * @covers ::<protected>
     */
    public function testConditionsNormalizer()
    {
        $this->assertMapper('conditionsNormalizer', 'Conditions\Normalizer', array(
            'conditions',
            'models'
        ));
    }
    
    /**
     * @covers ::preload
     * @covers ::<protected>
     */
    public function testPreload()
    {
        $this->assertMapper('preload', 'Preload', array(
            'relationships',
            'preloadMap'
        ));
    }
    
    /**
     * @covers ::update
     * @covers ::<protected>
     */
    public function testUpdate()
    {
        $this->assertMapper('update', 'Update', array(

        ));
    }
    
    /**
     * @covers ::cascadeDelete
     * @covers ::<protected>
     */
    public function testCascadeDelete()
    {
        $this->assertMapper('cascadeDelete', 'Cascade\Mapper\Delete', array(
            'mappers',
            'relationships',
            'models',
            'planners',
            'steps',
            'cascadeMap' => 'cascadeDeleteMap'
        ));
    }
    
    /**
     * @covers ::cascadePAth
     * @covers ::<protected>
     */
    public function testCascadePath()
    {
        $path = $this->mappers->cascadePath();
        $this->assertInstance($path, '\PHPixie\ORM\Mappers\Cascade\Path', array(
            'mappers' => $this->mappers,
        ));
        
        $this->assertSame(false, $path === $this->mappers->cascadePath());
    }
    
    protected function assertMapper($name, $class, $dependencyMap) {
        $propertyMap = array();
        foreach($dependencyMap as $key => $dependencyName) {
            if(!is_numeric($key)) {
                $property = $key;
            }else{
                $property = $dependencyName;
            }
            $propertyMap[$property] = $this->dependencies[$dependencyName];
        }
        
        $mapper = $this->mappers->$name();
        $this->assertInstance($mapper, '\PHPixie\ORM\Mappers\\'.$class, $propertyMap);
        $this->assertSame($mapper, $this->mappers->$name());
    }
}