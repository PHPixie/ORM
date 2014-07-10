<?php

namespace PHPixieTests\ORM\Relationships\Types;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Types\ManyToMany
 */
class RelationshipTest extends \PHPixieTests\ORM\Relationships\RelationshipTest
{
    protected function getRelationship()
    {
        return new \PHPixie\ORM\Relationships\Types\ManyToMany($this->ormBuilder);
    }
}