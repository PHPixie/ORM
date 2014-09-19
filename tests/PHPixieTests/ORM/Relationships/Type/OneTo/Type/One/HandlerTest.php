<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Type\One;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Handler
 */
class HandlerTest extends \PHPixieTests\ORM\Relationships\Type\OneTo\HandlerTest
{
    protected $itemSide = 'item';
    protected $ownerPropertyName = 'ownerItemProperty';
    
    protected function linkTest()
    {
        $owner = $this->getProperty('owner');
        $item  = $this->getProperty('item');
        
        $this->expectSetValue($owner, $item);
        $this->expectSetValue($item, $owner);
    }
    
    protected function getProperty($side, $hasProperty = true, $ownerLoaded = false, $value = null) {
        $model = $this->getDatabaseModel();
        return $this->addSingleProperty($model, $side, $hasProperty, $ownerLoaded, $value['model']);
    }
    
    protected function getSingleProperty($type)
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property\Model');
    }
    
    protected function getPreloader($type)
    {
    
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Preloader');
    }

    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Side\Config');
    }

    protected function getSide()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Side');
    }

    protected function getRelationship()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneToMany');
    }

    protected function getHandler()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Handler(
            $this->ormBuilder,
            $this->repositories,
            $this->planners,
            $this->plans,
            $this->steps,
            $this->loaders,
            $this->relationship,
            $this->groupMapper,
            $this->cascadeMapper
        );
    }
}
