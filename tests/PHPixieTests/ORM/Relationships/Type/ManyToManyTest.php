<?php

namespace PHPixieTests\ORM\Relationships\Type;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\ManyToMany
 */
class RelationshipTest extends \PHPixieTests\ORM\Relationships\RelationshipTest
{
    protected function getRelationship()
    {
        return new \PHPixie\ORM\Relationships\Type\ManyToMany($this->ormBuilder);
    }
}