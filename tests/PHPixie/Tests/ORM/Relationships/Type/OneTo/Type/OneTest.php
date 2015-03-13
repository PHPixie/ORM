<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Type;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\One
 */
class OneTest extends \PHPixie\Tests\ORM\Relationships\Type\OneToTest
{
    protected $handlerClass = '\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Handler';
    protected $configClass  = '\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Side\Config';
    
    protected $sides = array(
        'owner' => '\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Side',
        'item'  => '\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Side'
    );
    
    protected $entityProperties = array(
        'owner' => '\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property\Entity',
        'item'  => '\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property\Entity'
    );
    
    /**
     * @covers ::queryProperty
     * @covers ::<protected>
     */
    public function testQueryProperty()
    {
        $query = $this->getQuery();
        $this->propertyTest('query', $query, array(
            'owner' => '\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property\Query',
            'item'  => '\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property\Query'
        ));
    }
    
    protected function preloaderTest($sides, $modelConfig, $result, $loader)
    {
        $classMap = array(
            'owner' => '\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Preloader\Owner',
            'item'  => '\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Preloader\Item'
        );
        
        foreach($sides as $side) {
            $class = $classMap[$side->type()];
            $preloader = $this->relationship->preloader($side, $modelConfig, $result, $loader);
            $this->assertInstanceOf($class, $preloader);
            
            $this->assertProperties($preloader, array(
                'side'        => $side,
                'modelConfig' => $modelConfig,
                'result'      => $result,
                'loader'      => $loader,
            ));
        }
    }
    
    protected function configSlice()
    {
        return $this->getConfigSlice(array(
            'owner' => 'fairy',
            'item'  => 'flower'
        ));
    }
    
    protected function getEntity()
    {
        return $this->getDatabaseEntity();
    }
    
    protected function relationship()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\One(
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