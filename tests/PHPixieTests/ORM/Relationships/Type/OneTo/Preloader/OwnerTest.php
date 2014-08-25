<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Preloader;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Preloader\Owner
 */
abstract class OwnerTest extends \PHPixieTests\ORM\Relationships\Relationship\Preloader\Result\SingleTest
{
    protected $pivotResult;
    
    protected $configData = array(
        'ownerModel'       => 'fairy',
        'itemModel'        => 'flower',
        'itemKey'          => 'fairy_id',
        'ownerProperty'    => 'items',
        'itemProperty'     => 'fairy',
    );
    
    public function setUp()
    {
        $this->pivotResult = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result\Reusable');
        parent::setUp();
    }
    
    protected function prepareMap()
    {
        foreach($this->models as $id => $model) {
            $model->fairy_id = $this->map[$id];
        }
        
        $repository = $this->abstractMock('\PHPixie\ORM\Repositories\Type\Database');
        $this->method($this->loader, 'repository', $repository, array(), 0);
        
        $loaderResult = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result\Reusable');
        $this->method($this->loader, 'reusableResult', $loaderResult, array(), 1);
        
        $this->method($repository, 'idField', 'id', array(), 0);
        $this->method($loaderResult, 'getField', array_keys($this->preloadedModels), array('id'), 0);
    }
    
    protected function side()
    {
        $config = $this->getConfig();
        $this->mapConfig($config, $this->configData);
        $side = $this->getSide();
        $this->method($side, 'config', $config, array());
        return $side;
    }
                      
    protected function getModel()
    {
        return $this->abstractMock('\PHPixie\ORM\Repositories\Type\Database\Model');
    }
    
    protected function loader()
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Repository\ReusableResult');
    }
    
    protected abstract function getSide();
    protected abstract function getConfig();
    
}