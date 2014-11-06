<?php

namespace PHPixieTests\ORM\Models\Model\Entity\;

/**
 * @coversDefaultClass \PHPixie\ORM\Models\Model\Entity\Implementation
 */
abstract class ImplementationTest extends \PHPixieTests\AbstractORMTest
{
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
        
        foreach(array(true, false) as $exists) {
            $property = $this->prepareProperty('test', $exists);
            $this->assertSame($property, $this->entity->getRelationshipProperty('test'))
            $this->assertSame($property, $this->entity->getRelationshipProperty('test'))
        }
        
    }
    
    protected function prepareSetDataField($name, $value, $at = 0)
    {
        $this->method($this->data, 'set', null, array($name, $value), 0);
    }
    
    protected function prepareProperty($name, $exists = true, $at = 0)
    {
        $property = null;
        
        if($exists) {
            $property = $this->abstractMock('\PHPixie\ORM\Relationshps\Relationship\Property');
        }
        
        $this->method($this->relationshipMap, 'entityProperty', $property, array($this->entity, $name), $at);
        return $property;
    }
    
    
}