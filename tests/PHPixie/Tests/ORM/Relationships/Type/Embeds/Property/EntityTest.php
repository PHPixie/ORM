<?php

namespace PHPixie\Tests\ORM\Relationships\Type\Embeds\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Property\Entity
 */
abstract class EntityTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\Property\EntityTest
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
