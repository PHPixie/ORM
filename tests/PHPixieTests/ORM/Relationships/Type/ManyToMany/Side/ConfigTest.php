<?php

namespace PHPixieTests\ORM\Relationships\Type\ManyToMany\Side;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\ManyToMany\Side\Config
 */
class ConfigTest extends \PHPixieTests\ORM\Relationships\Relationship\Implementation\Side\ConfigTest
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
        
        $this->sets[] = array(
            $this->slice(array(
                'left'  => 'fairy',
                'leftOptions.property' => 'favourites',
                
                'right' => 'flower',
                'rightOptions.property' => 'owners',
                
                'pivot' => 'favouriteFlowers',
                'pivotOptions.connection' => 'mysql',
                'pivotOptions.leftKey' => 'fairyId',
                'pivotOptions.rightKey' => 'flowerId',
            )),
            array(
                'leftModel'       => 'fairy',
                'leftProperty'    => 'favourites',
                'leftPivotKey'    => 'fairyId',

                'rightModel'      => 'flower',
                'rightProperty'   => 'owners',
                'rightPivotKey'   => 'flowerId',

                'pivot'           => 'favouriteFlowers',
                'pivotConnection' => 'mysql'
            )
        );
        
        parent::setUp();
    }
    
    protected function getConfig($slice)
    {
        return new \PHPixie\ORM\Relationships\Type\ManyToMany\Side\Config($this->inflector, $slice);
    }
}