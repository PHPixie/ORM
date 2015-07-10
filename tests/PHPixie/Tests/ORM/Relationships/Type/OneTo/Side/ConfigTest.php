<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Side;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Side\Config
 */
abstract class ConfigTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\Side\ConfigTest
{
    protected $itemOptionName;
    protected $ownerProperty;
    protected $defaultOwnerProperty;

    public function setUp()
    {
        $this->sets[] = array(
            $this->slice(array(
                
            ), array(
                'owner'                => 'fairy',
                 $this->itemOptionName => 'flower'
            )),
            array(
                'ownerModel'       => 'fairy',
                'itemModel'        => 'flower',
                
                'ownerKey'         => 'fairyId',
                'onDelete'         => 'update',
                
                $this->ownerProperty    => $this->defaultOwnerProperty,
                'itemOwnerProperty'     => 'fairy'
            )
        );

        $itemOptionsPrefix = $this->itemOptionName.'Options';

        $this->sets[] = array(
            $this->slice(array(
                $itemOptionsPrefix.'.ownerProperty' => 'pixie',
                $itemOptionsPrefix.'.ownerKey'      => 'ownerId',
                $itemOptionsPrefix.'.onOwnerDelete' => 'delete',

                'ownerOptions.'.$this->itemOptionName.'Property' => 'plants'
            ), array(
                'owner'               => 'fairy',
                $this->itemOptionName => 'flower',            
            )),
            array(
                'ownerModel'       => 'fairy',
                'itemModel'        => 'flower',
                
                'ownerKey'          => 'ownerId',
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
