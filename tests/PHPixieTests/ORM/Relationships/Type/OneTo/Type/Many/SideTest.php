<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Type\Many;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side
 */
class SideTest extends \PHPixieTests\ORM\Relationships\Type\OneTo\SideTest
{
    protected $itemSideName = 'items';
    protected $ownerProperty = 'flowers';

    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Config');
    }

    protected function getSide($type)
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side($type, $this->config);
    }
}
