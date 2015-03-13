<?php

namespace PHPixie\Tests\ORM\Wrappers\Type\Embedded;

/**
 * @coversDefaultClass \PHPixie\ORM\Wrappers\Type\Embedded\Entity
 */
class EntityTest extends \PHPixie\Tests\ORM\Wrappers\Model\EntityTest
{
    public function setUp()
    {
        $this->methods = array_merge($this->methods, array(
            array('setOwnerRelationship', false, array('a', 'b')),
            array('unsetOwnerRelationship', false, array()),
            array('owner', true, array()),
            array('ownerPropertyName', true, array()),
        ));
        
        parent::setUp();
    }
    
    protected function entity()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Embedded\Entity');
    }
    
    protected function wrapper()
    {
        return new \PHPixie\ORM\Wrappers\Type\Embedded\Entity($this->entity);
    }
}