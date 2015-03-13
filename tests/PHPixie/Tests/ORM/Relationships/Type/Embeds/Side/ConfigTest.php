<?php

namespace PHPixie\Tests\ORM\Relationships\Type\Embeds\Side;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Side\Config
 */
abstract class ConfigTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\Side\ConfigTest
{
    public function setUp()
    {
        $this->sets[] = array(
            $this->slice(array(
                
            ),array(
                'owner'                => 'fairy',
                 $this->itemOptionName => 'flower'
            )),
            array(
                'ownerModel'       => 'fairy',
                'itemModel'        => 'flower',

                $this->ownerProperty    => $this->defaultOwnerProperty,
                'path' => $this->defaultOwnerProperty
            )
        );

        $itemOptionsPrefix = $this->itemOptionName.'Options';

        $this->sets[] = array(
            $this->slice(array(
                $itemOptionsPrefix.'.path' => 'favourite',
                'ownerOptions.'.$this->itemOptionName.'Property' => 'plants'
            ), array(
                'owner'               => 'fairy',
                $this->itemOptionName => 'flower',            
            )),
            array(
                'ownerModel'       => 'fairy',
                'itemModel'        => 'flower',
                
                $this->ownerProperty    => 'plants',
                'path' => 'favourite'
                
            )
        );

        parent::setUp();
    }
    
    public function testOwnerProperty()
    {
        $data = $this->sets[0][1];
        $this->assertEquals($data[$this->ownerProperty], $this->config->ownerProperty());
    }
}