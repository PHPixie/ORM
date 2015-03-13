<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Type;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many
 */
class ManyTest extends \PHPixie\Tests\ORM\Relationships\Type\OneToTest
{
    protected $handlerClass = '\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Handler';
    protected $configClass  = '\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side\Config';
    
    protected $sides = array(
        'owner' => '\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side',
        'items' => '\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side'
    );
    
    protected $entityProperties = array(
        'owner' => '\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Entity\Owner',
        'items' => '\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Entity\Items'
    );
    
    /**
     * @covers ::queryProperty
     * @covers ::<protected>
     */
    public function testQueryProperty()
    {
        $query = $this->getQuery();
        $this->propertyTest('query', $query, array(
            'owner' => '\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Query\Owner',
            'items' => '\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Query\Items'
        ));
    }
    
    /**
     * @covers ::ownerPreloadValue
     * @covers ::<protected>
     */
    public function testOwnerPreloadValue()
    {
        $propertyName = 'pixie';
        $owner = $this->getEntity();
        
        $ownerPreloadValue = $this->relationship->ownerPreloadValue($propertyName, $owner);
        $this->assertInstanceOf('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Value\Preload\Owner', $ownerPreloadValue);
        $this->assertProperties($ownerPreloadValue, array(
            'propertyName' => $propertyName,
            'owner'        => $owner
        ));
    }
    
    /**
     * @covers ::ownerPropertyPreloader
     * @covers ::<protected>
     */
    public function testOwnerPropertyPreloader()
    {
        $owner = $this->getEntity();
        
        $ownerPropertyPreloader = $this->relationship->ownerPropertyPreloader($owner);
        $this->assertInstanceOf('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader\Property\Owner', $ownerPropertyPreloader);
        $this->assertProperties($ownerPropertyPreloader, array(
            'owner' => $owner
        ));
    }
    
    protected function preloaderTest($sides, $modelConfig, $result, $loader)
    {
        $classMap = array(
            'owner' => '\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader\Owner',
            'items' => '\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader\Items'
        );
        
        foreach($sides as $side) {
            $class = $classMap[$side->type()];
            $preloader = $this->relationship->preloader($side, $modelConfig, $result, $loader);
            $this->assertInstanceOf($class, $preloader);
            
            $properties = array(
                'side'        => $side,
                'modelConfig' => $modelConfig,
                'result'      => $result,
                'loader'      => $loader,
            );
            
            if($side->type() === 'items') {
                $properties['loaders'] = $this->loaders;
            }
            
            $this->assertProperties($preloader, $properties);
        }
    }
    
    protected function configSlice()
    {
        return $this->getConfigSlice(array(
            'owner' => 'fairy',
            'items' => 'flower'
        ));
    }
    
    protected function getEntity()
    {
        return $this->getDatabaseEntity();
    }
    
    protected function relationship()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many(
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