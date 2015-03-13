<?php

namespace PHPixie\Tests\ORM\Models\Model\Implementation;

/**
 * @coversDefaultClass \PHPixie\ORM\Models\Model\Implementation\Entity
 */
abstract class EntityTest extends \PHPixie\Test\Testcase
{
    protected $entityPropertyMap;
    protected $config;
    protected $data;
    
    protected $entity;
    
    protected $configData = array(
        'model' => 'fairy'
    );
    
    protected $propertyNames = array();
    
    public function setUp()
    {
        $this->entityPropertyMap = $this->quickMock('\PHPixie\ORM\Maps\Map\Property\Entity');
        $this->config = $this->config();
        $this->data = $this->getData();
        
        for($i=0; $i<4; $i++) {
            $this->propertyNames[] = 'test'.$i;
        }
        
        $this->entity = $this->entity();
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::modelName
     * @covers ::<protected>
     */
    public function testModelName()
    {
        $this->assertSame($this->configData['model'], $this->entity->modelName());
    }
    
    /**
     * @covers ::data
     * @covers ::<protected>
     */
    public function testData()
    {
        $this->assertSame($this->data, $this->entity->data());
    }
    
    /**
     * @covers ::getField
     * @covers ::<protected>
     */
    public function testGetField()
    {
        $this->prepareGetDataField('name', 'Trixie', 'Blum');
        $this->assertEquals('Blum', $this->entity->getField('name', 'Trixie'));
        
        $this->prepareGetDataField('name', null, null);
        $this->assertEquals(null, $this->entity->getField('name'));
    }
    
    /**
     * @covers ::getRequiredField
     * @covers ::<protected>
     */
    public function testGetRequiredField()
    {
        $this->prepareGetRequiredDataField('name', 'Blum');
        $this->assertEquals('Blum', $this->entity->getRequiredField('name'));
    }
    
    /**
     * @covers ::setField
     * @covers ::<protected>
     */
    public function testSetField()
    {
        $this->prepareSetDataField('test', 5);
        $this->assertSame($this->entity, $this->entity->setField('test', 5));
    }
    
    /**
     * @covers ::getRelationshipProperty
     * @covers \PHPixie\ORM\Models\Model\Implementation\Entity::getRelationshipProperty
     * @covers ::<protected>
     */
    public function testGetRelationshipProperty()
    {
        $this->prepareRequirePropertyNames();
        
        $this->assertSame(null, $this->entity->getRelationshipProperty('test1', false));
        
        foreach(array(true, false) as $key => $exists) {
            $name = 'test'.$key;
            $at = $key === 0 ? 1 : 0;
            $property = $this->prepareProperty($name, 0, $exists);
            
            $this->assertSame($property, $this->entity->getRelationshipProperty($name));
            $this->assertSame($property, $this->entity->getRelationshipProperty($name));
        }
        
        $this->setExpectedException('\PHPixie\ORM\Exception\Relationship');
        $this->entity->getRelationshipProperty('test5');
    }
    
    /**
     * @covers ::asObject
     * @covers ::<protected>
     */
    public function testAsObject()
    {
        $this->asObjectTest();
    }
    
    /**
     * @covers ::asObject
     * @covers ::<protected>
     */
    public function testAsObjectRecursive()
    {
        $this->asObjectTest(true);
    }
    
    /**
     * @covers ::__get
     * @covers ::<protected>
     */
    public function testGet()
    {
        $this->prepareRequirePropertyNames();
        
        $property = $this->prepareProperty('test1', 1);
        $this->assertSame($property, $this->entity->test1);
        $this->assertSame($property, $this->entity->test1);
        
        $this->prepareGetRequiredDataField('name', 'Blum');
        $this->assertSame('Blum', $this->entity->name);
    }
    
    /**
     * @covers ::__call
     * @covers ::<protected>
     */
    public function testCall()
    {
        $this->prepareRequirePropertyNames();
        
        $property = $this->prepareProperty('test1', 1);
        $this->method($property, '__invoke', 'test', array(), 0);
        $this->assertEquals('test', $this->entity->test1());
    }
    
    /**
     * @covers ::__set
     * @covers ::<protected>
     */
    public function testSet()
    {
        $this->prepareSetDataField('test', 5);
        $this->entity->test = 5;
    }
    
    protected function asObjectTest($recursive = false)
    {
        $this->prepareRequirePropertyNames();
        
        $expected = array('name' => 'Trixie');
        $data = (object) $expected;

        $this->method($this->data, 'data', $data, array(), 0);

        $propertyParams = array(
            array(false, false),
            array(true, false),
            array(true, true),
        );

        $properties = array();

        foreach($propertyParams as $key => $params) {
            $name = 'test'.$key;
            $at = $key === 0 ? 1 : 0;
            $properties[$key] = $this->prepareProperty($name, $at, $params[0], $params[1]);
            $this->entity->getRelationshipProperty($name);
        }

        if($recursive) {
            $propertyData = array('name' => 'Blum');
            $expected['test2'] = $propertyData;

            $this->method($properties[2], 'asData', (object) $propertyData, array(true), 1);
            
            $object = (array) $this->entity->asObject(true);
            $object['test2'] = (array) $object['test2'];
            
        }else{
            $object = (array) $this->entity->asObject();
        }

        $this->assertEquals($expected, $object);
    }
    
    protected function prepareGetDataField($name, $default, $return)
    {
        $this->method($this->data, 'get', $return, array($name, $default), 0);
    }
    
    protected function prepareGetRequiredDataField($name, $return)
    {
        $this->method($this->data, 'getRequired', $return, array($name), 0);
    }
    
    protected function prepareSetDataField($name, $value, $at = 0)
    {
        $this->method($this->data, 'set', null, array($name, $value), 0);
    }
    
    protected function prepareRequirePropertyNames()
    {
        $this->method($this->entityPropertyMap, 'getPropertyNames', $this->propertyNames, array($this->configData['model']), 0);
    }
    
    protected function prepareProperty($name, $at = 0, $withAsData = false, $isLoaded = true)
    {
        if($withAsData) {
            $property = $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Property\Entity\Data');
        }else{
            $property = $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Property\Entity');
        }
        
        $this->method($property, 'isLoaded', $isLoaded, array());
        
        $this->method($this->entityPropertyMap, 'property', $property, array($this->entity, $name), $at, true);
        return $property;
    }
    
    protected function config()
    {
        $config = $this->getConfig();
        foreach($this->configData as $key => $value) {
            $config->$key = $value;
        }
        return $config;
    }
    
    abstract protected function getData();
    abstract protected function getConfig();
    abstract protected function entity();
}