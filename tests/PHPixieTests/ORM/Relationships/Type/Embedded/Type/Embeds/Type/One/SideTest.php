<?php

namespace PHPixieTests\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Side
 */
class SideTest extends \PHPixieTests\ORM\Relationships\Type\Embedded\Type\Embeds\SideTest
{
    protected $ownerProperty = 'flower';
    protected $relationshipType = 'embedsOne';

    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Side\Config');
    }

    protected function getSide($type)
    {
        return new \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Side($type, $this->config);
    }
}
