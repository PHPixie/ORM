<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Type\One\Preloader;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Preloader\Owner
 */
class OwnerTest extends \PHPixie\Tests\ORM\Relationships\Type\OneTo\Preloader\OwnerTest
{
    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Side\Config');
    }
    
    protected function getSide()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Side');
    }
    
    protected function getProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property\Entity');
    }
    
    protected function preloader()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Preloader\Owner(
            $this->side,
            $this->modelConfig,
            $this->result,
            $this->loader
        );
    }
    
}