<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Type\Many\Side;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side\Config
 */
class ConfigTest extends \PHPixieTests\ORM\Relationships\Type\OneTo\Side\ConfigTest
{
    protected $itemOptionName = 'items';
    protected $defaultOwnerProperty = 'flowers';
    protected $plural = array(
        'flower' => 'flowers'
    );

    protected function getConfig($slice)
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side\Config($this->inflector, $slice);
    }
}
