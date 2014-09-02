<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Preloader;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Preloader\Owner
 */
abstract class OwnerTest extends \PHPixieTests\ORM\Relationships\Relationship\Preloader\Result\SingleTest
{
    protected $configData = array(
        'ownerModel'       => 'fairy',
        'itemModel'        => 'flower',
        'ownerKey'         => 'fairy_id',
        'ownerProperty'    => 'items',
        'itemProperty'     => 'fairy',
    );
    
    protected function prepareMap()
    {
        foreach($this->models as $id => $model) {
            $this->method($model, 'getField', $this->map[$id], array('fairy_id'));
        }
        
        $repository = $this->getDatabaseRepository();
        $this->method($this->loader, 'repository', $repository, array(), 0);
        
        $loaderResult = $this->getReusableResult();
        $this->method($this->loader, 'reusableResult', $loaderResult, array(), 1);
        
        $this->method($repository, 'idField', 'id', array(), 0);
        $this->method($loaderResult, 'getField', array_keys($this->preloadedModels), array('id'), 0);
    }
    
    protected function getModel()
    {
        return $this->abstractMock('\PHPixie\ORM\Repositories\Type\Database\Model');
    }
    
    protected function loader()
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Repository\ReusableResult');
    }
    
}