<?php

namespace PHPixieTests\ORM\Relationships\Types\ManyToMany\Side;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Types\ManyToMany\Side\Config
 */
class ConfigTest extends \PHPixieTests\ORM\Relationships\Relationship\Side\ConfigTest
{
    protected $plural = array(
        'fairy'  => 'fairies',
        'flower' => 'flowers',
    );
    
    public function setUp()
    {
        $this->sets[] = array(
            $this->slice(array(
                'left'  => 'fairy',
                'right' => 'flower'
            )),
            array(
                'leftModel'       => 'fairy',
                'leftProperty'    => 'flowers',
                'leftPivotKey'    => 'fairy_id',

                'rightModel'      => 'flower',
                'rightProperty'   => 'fairies',
                'rightPivotKey'   => 'flower_id',

                'pivot'           => 'fairies_flowers',
                'pivotConnection' => null
            )
        );
        
        parent::setUp();
    }
    
    protected function getConfig($slice)
    {
        return new \PHPixie\ORM\Relationships\Types\ManyToMany\Side\Config($this->inflector, $slice);
    }
}