<?php

namespace PHPixie\Tests\ORM\Relationships\Type\Embeds\Type\Many\Side;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Side\Config
 */
class ConfigTest extends \PHPixie\Tests\ORM\Relationships\Type\Embeds\Side\ConfigTest
{
    protected $itemOptionName = 'items';
    protected $ownerProperty  = 'ownerItemsProperty';
    protected $defaultOwnerProperty = 'flowers';
    
    protected $plural = array(
        'flower' => 'flowers'
    );

    protected function getConfig($slice)
    {
        return new \PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Side\Config($this->inflector, $slice);
    }
    
}
