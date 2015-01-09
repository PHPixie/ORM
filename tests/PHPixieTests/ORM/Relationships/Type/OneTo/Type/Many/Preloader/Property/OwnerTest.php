<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Type\Many\Preloader\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader\Property\Owner
 */
class OwnerTest extends \PHPixieTests\ORM\Relationships\Relationship\Implementation\Preloader\ValueTest
{
    protected function getProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property\Entity');
    }
    
    protected function preloader()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader\Property\Owner(
            $this->value
        );
    }
}