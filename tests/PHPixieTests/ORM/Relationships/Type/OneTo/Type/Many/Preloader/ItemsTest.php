<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Type\Many\Preloader;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader\Items
 */
class ItemsTest extends \PHPixieTests\ORM\Relationships\Relationship\Preloader\Result\Multiple\IdMapTest
{
    protected $configData = array(
        'ownerModel'     => 'fairy',
        'ownerProperty'  => 'flowers',
        'itemModel'      => 'flower',
        'itemProperty'   => 'fairies',
        'ownerKey'       => 'fairy_id',
    );
    
    protected function prepareMap()
    {
        foreach($this->models as $id => $model) {
            $this->method($model, 'id', $id, array());
        }
        
        $ownerKey = $this->configData['ownerKey'];
        
        $fields = array();
        foreach(array_keys($this->preloadedModels) as $id) {
            foreach($this->map as $ownerId => $ids) {
                if(in_array($id, $ids)) {
                    $fields[]=array(
                            'id'      => $id,
                            $ownerKey => $ownerId
                    );
                }
            }
        }
    
        $repository = $this->getRepository();
        $this->method($this->loader, 'repository', $repository, array(), 0);
        $this->method($repository, 'idField', 'id', array(), 0);
        $loaderResult = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result\Reusable');
        $this->method($this->loader, 'reusableResult', $loaderResult, array(), 1);
        $this->method($loaderResult, 'getFields', $fields, array(array('id', $ownerKey)), 0);
    }
    
    protected function relationship()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneToOne');
    }
    
    protected function loader()
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Repository\ReusableResult');
    }
    
    protected function getSide()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side');
    }
    
    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side\Config');
    }
    
    protected function preloader()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader\Items(
            $this->loaders,
            $this->relationship,
            $this->side,
            $this->loader
        );
    }
}