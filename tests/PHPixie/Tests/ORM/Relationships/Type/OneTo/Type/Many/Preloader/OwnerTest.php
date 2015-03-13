<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Type\Many\Preloader;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader\Owner
 */
class OwnerTest extends \PHPixie\Tests\ORM\Relationships\Type\OneTo\Preloader\OwnerTest
{
    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side\Config');
    }
    
    protected function getSide()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side');
    }
    
    protected function getProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Entity\Owner');
    }
    
    protected function preloader()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader\Owner(
            $this->side,
            $this->modelConfig,
            $this->result,
            $this->loader
        );
    }
    
}