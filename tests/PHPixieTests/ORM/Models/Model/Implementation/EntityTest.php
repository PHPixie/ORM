<?php

namespace PHPixieTests\ORM\Models\Model\Implementation;

/**
 * @coversDefaultClass \PHPixie\ORM\Models\Model\Implementation\Entity
 */
abstract class EntityTest extends \PHPixieTests\AbstractORMTest
{
    protected $relationshipMap;
    protected $config;
    protected $data;
    
    protected $entity;
    
    protected $configData = array(
        'modelName' => 'fairy'
    );
    
    public function setUp()
    {
        $this->relationshipMap = $this->quickMock('\PHPixie\ORM\Relationships\Map');
        $this->config = $this->config();
        $this->data = $this->getData();
        
        $this->entity = $this->entity();
    }
    
    /**
     * @covers ::modelName
     * @covers ::<protected>
     */
    public function testModelName()
    {
        $this->assertSame($this->configData['modelName'], $this->entity->modelName());
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
     * @covers ::setField
     * @covers ::<protected>
     */
    public function testSetField()
    {
        $this->prepareSetDataField('test', 5);
        $this->entity->setField('test', 5);
    }
    
    /**
     * @covers ::getRelationshipProperty
     * @covers ::<protected>
     */
    public function testGetRelationshipProperty()
    {
        $this->assertSame(null, $this->entity->getRelationshipProperty('test', false));
        
        foreach(array(true, false) as $key => $exists) {
            $name = 'test'.$key;
            $property = $this->prepareProperty($name, $exists);
            
            $this->assertSame($property, $this->entity->getRelationshipProperty($name));
            $this->assertSame($property, $this->entity->getRelationshipProperty($name));
        }
        
    }
    
    /**
     * @covers ::asObject
     * @covers ::<protected>
     */
    public function testAsObject()
    {
        $data = (object) array('name' => 'Trixie');
        $this->method($this->data, 'data', $data, array(), 0);
        
        $this->prepareProperty('test1', true, false);
        $this->entity->getRelationshipProperty('test1');
        
        $this->prepareProperty('test2', false, false);
        $this->entity->getRelationshipProperty('test2');
        
        $property = $this->prepareProperty('test3', true, true);
        $this->entity->getRelationshipProperty('test3');
        
        $propertyData = (object) array('name' => 'Blum');
        $this->method($property, 'asData', $propertyData, array(true), 0);
        
        $object = (array) $this->entity->asObject(true);
        $object['test3'] = (array) $object['test3'];
        
        $this->assertEquals(array(
            'name' => 'Trixie',
            'test3' => array('name' => 'Blum')
        ), $object);
    }
    
    protected function prepareSetDataField($name, $value, $at = 0)
    {
        $this->method($this->data, 'set', null, array($name, $value), 0);
    }
    
    protected function prepareProperty($name, $exists = true, $withAsData = false, $at = 0)
    {
        $property = null;
        
        if($exists) {
            if($withAsData) {
                $property = $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Property\Entity\Data');
            }else{
                $property = $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Property\Entity');
            }
        }
        
        $this->method($this->relationshipMap, 'entityProperty', $property, array($this->entity, $name), $at, true);
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