<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Type\One\Side;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Side\Config
 */
class ConfigTest extends \PHPixie\Tests\ORM\Relationships\Type\OneTo\Side\ConfigTest
{
    protected $itemOptionName = 'item';
    protected $ownerProperty  = 'ownerItemProperty';
    protected $defaultOwnerProperty = 'flower';
    
    protected function getConfig($slice)
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Side\Config($this->inflector, $slice);
    }
    
}
