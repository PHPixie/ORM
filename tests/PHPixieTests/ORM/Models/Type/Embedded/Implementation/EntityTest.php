<?php

namespace PHPixieTests\ORM\Models\Type\Embedded\Implementation;

/**
 * @coversDefaultClass \PHPixie\ORM\Models\Type\Embedded\Implementation\Entity
 */
class EntityTest extends \PHPixieTests\ORM\Models\Model\Implementation\EntityTest
{
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
}