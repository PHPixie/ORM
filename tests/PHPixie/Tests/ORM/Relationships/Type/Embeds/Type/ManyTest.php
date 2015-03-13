<?php

namespace PHPixie\Tests\ORM\Relationships\Type\Embeds\Type;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Type\Many
 */
class ManyTest extends \PHPixie\Tests\ORM\Relationships\Type\EmbedsTest
{
    protected $handlerClass = '\PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Handler';
    protected $configClass  = '\PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Side\Config';
    
    protected $sides = array(
        'items' => '\PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Side'
    );
    
    protected $entityProperties = array(
        'items' => '\PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Property\Entity\Items'
    );
    
    protected $preloaderClass = '\PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Preloader';
    protected $preloadResultClass = '\PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Preload\Result';
    
    protected function configSlice()
    {
        return $this->getConfigSlice(array(
            'owner' => 'fairy',
            'items'  => 'flower'
        ));
    }
    
    protected function relationship()
    {
        return new \PHPixie\ORM\Relationships\Type\Embeds\Type\Many(
            $this->configs,
            $this->models,
            $this->planners,
            $this->plans,
            $this->steps,
            $this->loaders,
            $this->mappers,
            $this->relationship
        );
    }
}