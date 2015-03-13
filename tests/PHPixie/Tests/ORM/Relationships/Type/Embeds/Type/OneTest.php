<?php

namespace PHPixie\Tests\ORM\Relationships\Type\Embeds\Type;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Type\One
 */
class OneTest extends \PHPixie\Tests\ORM\Relationships\Type\EmbedsTest
{
    protected $handlerClass = '\PHPixie\ORM\Relationships\Type\Embeds\Type\One\Handler';
    protected $configClass  = '\PHPixie\ORM\Relationships\Type\Embeds\Type\One\Side\Config';
    
    protected $sides = array(
        'item' => '\PHPixie\ORM\Relationships\Type\Embeds\Type\One\Side'
    );
    
    protected $entityProperties = array(
        'item' => '\PHPixie\ORM\Relationships\Type\Embeds\Type\One\Property\Entity\Item'
    );
    
    protected $preloaderClass = '\PHPixie\ORM\Relationships\Type\Embeds\Type\One\Preloader';
    protected $preloadResultClass = '\PHPixie\ORM\Relationships\Type\Embeds\Type\One\Preload\Result';
    
    protected function configSlice()
    {
        return $this->getConfigSlice(array(
            'owner' => 'fairy',
            'item'  => 'flower'
        ));
    }
    
    protected function relationship()
    {
        return new \PHPixie\ORM\Relationships\Type\Embeds\Type\One(
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