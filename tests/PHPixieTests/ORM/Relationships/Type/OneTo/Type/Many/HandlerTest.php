<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Type\Many;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Handler
 */
class HandlerTest extends \PHPixieTests\ORM\Relationships\Type\OneTo\HandlerTest
{
    protected $itemSide = 'items';

    protected function getPreloader(){
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader');
    }

    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side\Config');
    }

    protected function getSide()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side');
    }

    protected function getRelationship()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many');
    }

    protected function getHandler()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Handler(
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
