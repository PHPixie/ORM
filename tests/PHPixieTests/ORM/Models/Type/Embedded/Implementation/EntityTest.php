<?php

namespace PHPixieTests\ORM\Models\Type\Embedded\Implementation;

/**
 * @coversDefaultClass \PHPixie\ORM\Models\Type\Embedded\Implementation\Entity
 */
class EntityTest extends \PHPixieTests\ORM\Models\Model\Implementation\EntityTest
{
    /**
     * @covers ::__construct
     * @covers \PHPixie\ORM\Models\Model\Implementation\Entity::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    protected function getData()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Types\Document');
    }
    
    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Model\Type\Embedded\Config');
    }
    
    protected function entity()
    {
        return new \PHPixie\ORM\Models\Type\Embedded\Implementation\Entity($this->relationshipMap, $this->config, $this->data);
    }
    
    /**
     * @covers ::setOwnerRelationship
     * @covers ::owner
     * @covers ::ownerPropertyName
     * @covers ::unsetOwnerRelationship
     * @covers ::<protected>
     */
    public function testOwnerRelationship()
    {
        $this->assertOwnerRelationship(null, null);
        
        $owner = $this->getEntity();
        $this->entity->setOwnerRelationship($owner, 'pixie');
        $this->assertOwnerRelationship($owner, 'pixie');
        
        $this->entity->unsetOwnerRelationship();
        $this->assertOwnerRelationship(null, null);
    }
    
    protected function assertOwnerRelationship($owner, $name)
    {
        $this->assertSame($owner, $this->entity->owner());
        $this->assertSame($name, $this->entity->ownerPropertyName());
    }
    
    protected function getEntity()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Embedded\Entity');   
    }
}