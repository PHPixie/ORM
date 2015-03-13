<?php

namespace PHPixie\Tests\ORM\Maps\Map\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Maps\Map\Property\Entity
 */
class EntityTest extends \PHPixie\Tests\ORM\Maps\Map\PropertyTest
{
    
    /**
     * @covers ::property
     * @covers ::<protected>
     */
    public function testProperty()
    {
        $side = $this->side('fairy', 'trees');
        $entity = $this->getEntity();
        $relationship = $this->prepareRelationship('oneToOne');
        $property = $this->getProperty();
        
        $this->map->add($side);
        
        $this->method($entity, 'modelName', 'fairy', array(), 0);
        $this->method($side, 'relationshipType', 'oneToOne', array(), 0);
        $this->method($relationship, 'entityProperty', $property, array($side, $entity), 0, true);

        $this->assertEquals($property, $this->map->property($entity, 'trees'));
    }
    
    protected function getEntity()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Model\Entity');
    }
    
    protected function getProperty()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Property\Entity');
    }
    
    protected function getSide()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Side\Property\Entity');
    }
    
    protected function map()
    {
        return new \PHPixie\ORM\Maps\Map\Property\Entity($this->relationships);
    }
}