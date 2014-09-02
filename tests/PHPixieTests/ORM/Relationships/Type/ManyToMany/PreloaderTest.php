<?php

namespace PHPixieTests\ORM\Relationships\Type\ManyToMany;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\ManyToMany\Preloader
 */
class PreloaderTest extends \PHPixieTests\ORM\Relationships\Relationship\Preloader\Result\Multiple\IdMapTest
{
    protected $pivotResult;
    
    protected $configData = array(
        'leftModel'     => 'fairy',
        'leftProperty'  => 'flowers',
        'leftPivotKey'  => 'fairy_id',
        'rightModel'    => 'flower',
        'rightProperty' => 'fairies',
        'rightPivotKey' => 'flower_id',
        'pivot'         => 'fairies_flowers'
    );
    
    public function setUp()
    {
        $this->pivotResult = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result\Reusable');
        parent::setUp();
    }
    
    protected function prepareMap($type = 'left')
    {
        foreach($this->models as $id => $model) {
            $this->method($model, 'id', $id, array());
        }
        
        $this->method($this->side, 'type', $type, array(), 0);
        
        $opposing = $type === 'left' ? 'right' : 'left';
        
        $ownerKey = $this->configData[$opposing.'PivotKey'];
        $itemKey = $this->configData[$type.'PivotKey'];
        
        $fields = array();
        foreach($this->map as $ownerId => $ids) {
            foreach($ids as $id) {
                $fields[] = array(
                                $ownerKey => $ownerId,
                                $itemKey  => $id,
                            );
            }
        }
        
        $this->method($this->pivotResult, 'getFields', $fields, array(array($ownerKey, $itemKey)), 0);
        
        $repository = $this->getRepository();
        $this->method($this->loader, 'repository', $repository, array(), 0);
        
        $loaderResult = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result\Reusable');
        $this->method($this->loader, 'reusableResult', $loaderResult, array(), 1);
        
        $this->method($repository, 'idField', 'id', array(), 0);
        $this->method($loaderResult, 'getField', array_keys($this->preloadedModels), array('id'), 0);
        
    }
    
    protected function relationship()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany');
    }
    
    protected function loader()
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Repository\ReusableResult');
    }
    
    protected function getSide()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany\Side');
    }
    
    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany\Side\Config');
    }
    
    protected function preloader()
    {
        return new \PHPixie\ORM\Relationships\Type\ManyToMany\Preloader(
            $this->loaders,
            $this->relationship,
            $this->side,
            $this->loader,
            $this->pivotResult
        );
    }
}