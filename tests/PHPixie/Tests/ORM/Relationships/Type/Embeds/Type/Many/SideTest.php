<?php

namespace PHPixie\Tests\ORM\Relationships\Type\Embeds\Type\Many;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Side
 */
class SideTest extends \PHPixie\Tests\ORM\Relationships\Type\Embeds\SideTest
{
    protected $ownerProperty = 'flowers';
    protected $relationshipType = 'embedsMany';

    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Side\Config');
    }

    protected function getSide($type)
    {
        return new \PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Side($type, $this->config);
    }
}
