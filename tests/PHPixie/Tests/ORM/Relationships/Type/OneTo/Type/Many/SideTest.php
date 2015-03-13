<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Type\Many;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side
 */
class SideTest extends \PHPixie\Tests\ORM\Relationships\Type\OneTo\SideTest
{
    protected $itemSideName = 'items';
    protected $ownerProperty = 'flowers';
    protected $relationshipType = 'oneToMany';

    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side\Config');
    }

    protected function getSide($type)
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side($type, $this->config);
    }
}
