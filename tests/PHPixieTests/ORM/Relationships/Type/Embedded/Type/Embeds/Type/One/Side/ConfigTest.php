<?php

namespace PHPixieTests\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Side;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Side\Config
 */
class ConfigTest extends \PHPixieTests\ORM\Relationships\Type\Embedded\Type\Embeds\Side\ConfigTest
{
    protected $itemOptionName = 'item';
    protected $ownerProperty  = 'ownerItemProperty';
    protected $defaultOwnerProperty = 'flower';

    protected function getConfig($slice)
    {
        return new \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Side\Config($this->inflector, $slice);
    }
    
}
