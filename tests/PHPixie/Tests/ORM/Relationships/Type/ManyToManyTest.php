<?php

namespace PHPixie\Tests\ORM\Relationships\Type;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\ManyToMany
 */
class ManyToManyTest extends \PHPixie\Tests\ORM\Relationships\Relationship\ImplementationTest
{
    protected $handlerClass = '\PHPixie\ORM\Relationships\Type\ManyToMany\Handler';
    protected $configClass  = '\PHPixie\ORM\Relationships\Type\ManyToMany\Side\Config';
    
    protected $sides = array(
        'left'  => '\PHPixie\ORM\Relationships\Type\ManyToMany\Side',
        'right' => '\PHPixie\ORM\Relationships\Type\ManyToMany\Side'
    );
    
    protected $entityProperties = array(
        'left'  => '\PHPixie\ORM\Relationships\Type\ManyToMany\Property\Entity',
        'right' => '\PHPixie\ORM\Relationships\Type\ManyToMany\Property\Entity'
    );
    
    protected $preloaderClass = '\PHPixie\ORM\Relationships\Type\ManyToMany\Preloader';
    
    /**
     * @covers ::queryProperty
     * @covers ::<protected>
     */
    public function testQueryProperty()
    {
        $query = $this->getQuery();
        $this->propertyTest('query', $query, array(
            'left'  => '\PHPixie\ORM\Relationships\Type\ManyToMany\Property\Query',
            'right' => '\PHPixie\ORM\Relationships\Type\ManyToMany\Property\Query'
        ));
    }
    
    /**
     * @covers ::preloader
     * @covers ::<protected>
     */
    public function testPreloader()
    {
        $configSlice = $this->configSlice();
        $sides = $this->relationship->getSides($configSlice);
        $side = $sides[0];

        $modelConfig = $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Config');
        $result = $this->abstractMock('\PHPixie\ORM\Steps\Result');
        
        $loader = $this->getLoader();
        $pivotResult = $this->getReusableResult();
        
        $preloader = $this->relationship->preloader($side, $modelConfig, $result, $loader, $pivotResult);
        $this->assertInstanceOf('\PHPixie\ORM\Relationships\Type\ManyToMany\Preloader', $preloader);
        
        $this->assertProperties($preloader, array(
            'loaders'     => $this->loaders,
            'modelConfig' => $modelConfig,
            'result'      => $result,
            'side'        => $side,
            'loader'      => $loader,
            'pivotResult' => $pivotResult
        ));
    }
    
    protected function configSlice()
    {
        return $this->getConfigSlice(array(
            'left'  => 'fairy',
            'right' => 'flower'
        ));
    }
    
    protected function getEntity()
    {
        return $this->getDatabaseEntity();
    }
    
    protected function relationship()
    {
        return new \PHPixie\ORM\Relationships\Type\ManyToMany(
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