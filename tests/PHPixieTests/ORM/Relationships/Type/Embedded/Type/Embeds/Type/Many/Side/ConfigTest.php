<?php

namespace PHPixieTests\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Side;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Side\Config
 */
class ConfigTest extends \PHPixieTests\ORM\Relationships\Type\Embedded\Type\Embeds\Side\ConfigTest
{
    protected $itemOptionName = 'items';
    protected $ownerProperty  = 'ownerItemsProperty';
    protected $defaultOwnerProperty = 'flowers';
    
    protected $plural = array(
        'flower' => 'flowers'
    );

    protected function getConfig($slice)
    {
        return new \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Side\Config($this->inflector, $slice);
    }
    
}
