<?php

namespace PHPixieTests\ORM\Wrapper\Type\Embedded;

/**
 * @coversDefaultClass \PHPixie\ORM\Wrapper\Type\Embedded\Entity
 */
class EntityTest extends \PHPixieTests\ORM\Wrapper\Model\EntityTest
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
        return new \PHPixie\ORM\Wrapper\Type\Embedded\Entity($this->entity);
    }
}