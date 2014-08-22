<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Side;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Side\Config
 */
abstract class ConfigTest extends \PHPixieTests\ORM\Relationships\Relationship\Side\ConfigTest
{
    protected $itemOptionName;
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
                'itemKey'          => 'fairy_id',

                'ownerProperty'    => $this->defaultOwnerProperty,
                'itemProperty'     => 'fairy',
                'onDelete'         => 'update',
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
                'itemKey'          => 'owner_id',

                'ownerProperty'    => 'plants',
                'itemProperty'     => 'pixie',
                'onDelete'         => 'delete',
            )
        );

        parent::setUp();
    }
}
