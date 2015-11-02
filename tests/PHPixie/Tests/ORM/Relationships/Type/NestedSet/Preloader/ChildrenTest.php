<?php

namespace PHPixie\Tests\ORM\Relationships\Type\NestedSet\Preloader;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\NestedSet\Preloader\Children
 */
class ChildrenTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\Preloader\Result\Multiple\IdMapTest
{
    protected $configData = array(
        'model'    => 'fairy',
        'leftKey'  => 'left',
        'rightKey' => 'right'
    );
    
    protected function prepareMap()
    {
        foreach($this->entities as $id => $model) {
            $this->method($model, 'id', $id, array());
        }
        
        $ownerKey = $this->configData['ownerKey'];
        
        $fields = array();
        foreach(array_keys($this->preloadedEntities) as $id) {
            foreach($this->map as $ownerId => $ids) {
                if(in_array($id, $ids)) {
                    $fields[]=array(
                            'id'      => $id,
                            $ownerKey => $ownerId
                    );
                }
            }
        }
    
        $this->method($this->result, 'getFields', $fields, array(array('id', $ownerKey)), 0);
    }
    
    
    protected function getProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\NestedSet\Property\Entity\Children');
    }
    
    protected function relationship()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneToMany');
    }
    
    protected function loader()
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Repository\ReusableResult');
    }
    
    protected function getSide()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\NestedSet\Side');
    }
    
    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\NestedSet\Side\Config');
    }
    
    protected function preloader()
    {
        return new \PHPixie\ORM\Relationships\Type\NestedSet\Preloader\Children(
            $this->loaders,
            $this->side,
            $this->modelConfig,
            $this->result,
            $this->loader
        );
    }
}
