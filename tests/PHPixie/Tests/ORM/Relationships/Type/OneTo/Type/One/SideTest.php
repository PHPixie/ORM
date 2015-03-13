<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Type\One;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Side
 */
class SideTest extends \PHPixie\Tests\ORM\Relationships\Type\OneTo\SideTest
{
    protected $itemSideName = 'item';
    protected $ownerProperty = 'flower';
    protected $relationshipType = 'oneToOne';
    
    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Side\Config');
    }

    protected function getSide($type)
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Side($type, $this->config);
    }
}
