<?php

namespace PHPixieTests\ORM\Relationships\Type\Embedded\Type\Embeds\Side;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Side\Config
 */
abstract class ConfigTest extends \PHPixieTests\ORM\Relationships\Relationship\Side\ConfigTest
{
    public function setUp()
    {
        $this->sets[] = array(
            $this->slice(array(
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
                'owner'               => 'fairy',
                $this->itemOptionName => 'flower',

                $itemOptionsPrefix.'.path' => 'favourite',
                'ownerOptions.'.$this->itemOptionName.'Property' => 'plants'
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