<?php

namespace PHPixieTests\ORM\Wrapper\Type\Database;

/**
 * @coversDefaultClass \PHPixie\ORM\Wrapper\Type\Database\Entity
 */
class EntityTest extends \PHPixieTests\ORM\Wrapper\Model\EntityTest
{
    public function setUp()
    {
        $this->methods = array_merge($this->methods, array(
            array('id', true, array()),
            array('setId', false, array(5)),
            array('isDeleted', true, array()),
            array('setIsDeleted', false, array(true)),
            array('isNew', true, array()),
            array('setIsNew', false, array(true)),
            array('save', false, array()),
            array('delete', false, array()),
        ));
        
        parent::setUp();
    }
    
    protected function entity()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Entity');
    }
    
    protected function wrapper()
    {
        return new \PHPixie\ORM\Wrapper\Type\Database\Entity($this->entity);
    }
}