<?php

namespace PHPixieTests\ORM\Models\Model\Implementation;

/**
 * @coversDefaultClass \PHPixie\ORM\Models\Model\Implementation\Entity
 */
abstract class EntityTest extends \PHPixieTests\AbstractORMTest
{
    protected $entityMap;
    protected $config;
    protected $data;
    
    protected $entity;
    
    protected $configData = array(
        'model' => 'fairy'
    );
    
    protected $propertyNames = array();
    
    public function setUp()
    {
        $this->entityMap = $this->quickMock('\PHPixie\ORM\Maps\Map\Entity');
        $this->config = $this->config();
        $this->data = $this->getData();
        
        for($i=0; $i<4; $i++) {
            $this->propertyNames[] = 'test'.$i;
        }
        
        $this->method($this->entityMap, 'getPropertyNames', $this->propertyNames, array($this->configData['model']));
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
     * @covers ::<protected>
     */
    public function testGetRelationshipProperty()
    {
        $this->assertSame(null, $this->entity->getRelationshipProperty('test1', false));
        
        foreach(array(true, false) as $key => $exists) {
            $name = 'test'.$key;
            $property = $this->prepareProperty($name, $exists);
            
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
        $property = $this->prepareProperty('test1');
        $this->assertSame($property, $this->entity->test1);
        
        $this->prepareGetDataField('name', null, 'Blum');
        $this->assertSame('Blum', $this->entity->name);
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
            $properties[$key] = $this->prepareProperty($name, $params[0], $params[1]);
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
    
    protected function prepareSetDataField($name, $value, $at = 0)
    {
        $this->method($this->data, 'set', null, array($name, $value), 0);
    }
    
    protected function prepareProperty($name, $withAsData = false, $isLoaded = true, $at = 0)
    {
        if($withAsData) {
            $property = $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Property\Entity\Data');
        }else{
            $property = $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Property\Entity');
        }
        
        $this->method($property, 'isLoaded', $isLoaded, array());
        
        $this->method($this->entityMap, 'property', $property, array($this->entity, $name), $at, true);
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