<?php

namespace PHPixieTests\ORM\Relationships\Type\Embeds\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Property\Entity
 */
abstract class EntityTest extends \PHPixieTests\ORM\Relationships\Relationship\Implementation\Property\EntityTest
{
    protected function prepareLoad($value)
    {
        $this->method($this->handler, 'loadProperty', $this->setValueCallback($value), array($this->config, $this->entity), 0);
    }

    protected function getEntity()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Embedded\Entity');
    }
}
