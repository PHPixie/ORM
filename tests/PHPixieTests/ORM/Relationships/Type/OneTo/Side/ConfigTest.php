<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Side;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Side\Config
 */
abstract class ConfigTest extends \PHPixieTests\ORM\Relationships\Relationship\Implementation\Side\ConfigTest
{
    protected $itemOptionName;
    protected $ownerProperty;
    protected $defaultOwnerProperty;

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
                
                'ownerKey'         => 'fairy_id',
                'onDelete'         => 'update',
                
                $this->ownerProperty    => $this->defaultOwnerProperty,
                'itemOwnerProperty'     => 'fairy'
            )
        );

        $itemOptionsPrefix = $this->itemOptionName.'Options';

        $this->sets[] = array(
            $this->slice(array(
                'owner'               => 'fairy',
                $this->itemOptionName => 'flower',

                $itemOptionsPrefix.'.ownerProperty' => 'pixie',
                $itemOptionsPrefix.'.ownerKey'      => 'owner_id',
                $itemOptionsPrefix.'.onOwnerDelete' => 'delete',

                'ownerOptions.'.$this->itemOptionName.'Property' => 'plants'
            )),
            array(
                'ownerModel'       => 'fairy',
                'itemModel'        => 'flower',
                
                'ownerKey'          => 'owner_id',
                'onDelete'         => 'delete',
                
                $this->ownerProperty    => 'plants',
                'itemOwnerProperty'     => 'pixie'
                
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
