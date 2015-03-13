<?php

namespace PHPixie\Tests\ORM\Relationships\Relationship;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Implementation
 */
abstract class ImplementationTest extends \PHPixie\Test\Testcase
{
    protected $configs;
    protected $models;
    protected $planners;
    protected $plans;
    protected $steps;
    protected $loaders;
    protected $mappers;
    
    protected $relationship;
    
    protected $inflector;
    
    protected $handlerClass;
    protected $configClass;
    
    protected $sides;
    protected $entityProperties;

    public function setUp()
    {
        $this->configs = $this->quickMock('\PHPixie\ORM\Configs');
        $this->models = $this->quickMock('\PHPixie\ORM\Models');
        $this->planners = $this->quickMock('\PHPixie\ORM\Planners');
        $this->plans = $this->quickMock('\PHPixie\ORM\Plans');
        $this->steps = $this->quickMock('\PHPixie\ORM\Steps');
        $this->loaders = $this->quickMock('\PHPixie\ORM\Loaders');
        $this->mappers = $this->quickMock('\PHPixie\ORM\Mappers');
        
        $this->inflector = $this->quickMock('\PHPixie\ORM\Configs\Inflector');
        $this->method($this->inflector, 'plural', 'test');
        $this->method($this->inflector, 'singular', 'test');
        
        $this->method($this->configs, 'inflector', $this->inflector, array());
        
        $this->relationship = $this->relationship();
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
       
    }
    
    /**
     * @covers ::handler
     * @covers ::<protected>
     */
    public function testHandler()
    {
        $handler = $this->relationship->handler();
        $this->assertHandler($handler);
        
        $this->assertSame($handler, $this->relationship->handler());
    }
    
    /**
     * @covers ::getSides
     * @covers ::<protected>
     */
    public function testGetSides()
    {
        $configSlice = $this->configSlice();
        $sides = $this->relationship->getSides($configSlice);
        $this->assertSides($sides);
        
        for($i=1; $i < count($sides); $i++)
        {
            $this->assertSame($sides[0]->config(), $sides[$i]->config());
        }
        
        $this->assertConfig($sides[0]->config());
    }
    
    /**
     * @covers ::entityProperty
     * @covers ::<protected>
     */
    public function testEntityProperty()
    {
        $entity = $this->getEntity();
        $this->propertyTest('entity', $entity, $this->entityProperties);
    }
    
    protected function propertyTest($type, $owner, $classMap)
    {
        $configSlice = $this->configSlice();
        $sides = $this->relationship->getSides($configSlice);
        
        $handler = $this->relationship->handler();
        $method = $type.'Property';
        
        foreach($sides as $side) {
            $property = $this->relationship->$method($side, $owner);
            $this->assertInstanceOf($classMap[$side->type()], $property);
            $this->assertProperties($property, array(
                'handler' => $handler,
                'side' => $side,
                $type  => $owner
            ));
        }
    }
    
    protected function assertHandler($handler)
    {
        $this->assertInstanceOf($this->handlerClass, $handler);
        $this->assertProperties($handler, array(
            'models' => $this->models,
            'planners' => $this->planners,
            'plans' => $this->plans,
            'steps' => $this->steps,
            'loaders' => $this->loaders,
            'mappers' => $this->mappers,
            'relationship' => $this->relationship,
        ));
    }
    
    protected function assertSides($sides)
    {
        $i = 0;
        
        foreach($this->sides as $type => $class)
        {
            $this->assertSame($type, $sides[$i]->type());
            $this->assertInstanceOf($class, $sides[$i]);
            $i++;
        }
    }
    
    protected function assertConfig($config)
    {
        $this->assertInstanceOf($this->configClass, $config);
    }
    
    protected function assertProperties($object, $propertyMap)
    {
        foreach($propertyMap as $propertyName => $value) {
            $this->assertAttributeSame($value, $propertyName, $object);
        }
    }
    
    protected function getConfigSlice($data)
    {
        $slice = $this->abstractMock('\PHPixie\Slice\Data', array('get'));
        $slice
            ->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function($key) use($data){

                if(array_key_exists($key, $data))
                    return $data[$key];

                $args = func_get_args();
                if(count($args) == 2)
                    return $args[1];

                throw new \Exception("Key $key is not set.");
            }));
        return $slice;
    }
    
    protected function getDatabaseEntity()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Entity');
    }
    
    protected function getEmbeddedEntity()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Embedded\Entity');
    }
    
    protected function getQuery()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Query');
    }
    
    protected function getLoader()
    {
        return $this->abstractMock('\PHPixie\ORM\Loaders\Loader');
    }
    
    protected function getReusableResult()
    {
        return $this->abstractMock('\PHPixie\ORM\Steps\Result\Reusable');
    }
    
    abstract protected function configSlice();
    abstract protected function getEntity();
    
    abstract protected function relationship();
}