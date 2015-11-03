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
    
    protected $rows = array(
        array(1, 1, 11),
        array(4, 2, 3),
        array(5, 4, 9),
        array(9, 5, 6),
        array(10, 7, 8),
        array(6, 9, 10),
        
        array(2, 12, 17),
        array(7, 13, 14),
        array(8, 15, 16),
        
        array(3, 18, 19),
    );
    
    protected $preloadEntitiesCount = 7;
    
    protected function prepareMap()
    {
        foreach($this->entities as $id => $model) {
            $this->method($model, 'id', $id, array());
        }
        
        $leftKey  = $this->configData['leftKey'];
        $rightKey = $this->configData['rightKey'];
        
        $fields = array();
        foreach($this->rows as $row) {
            if($)
            $fields[]= array_combine(array('id', $leftKey, $rightKey), $row);
        }
    
        $this->method($this->result, 'getFields', $fields, array(array('id', $leftKey, $rightKey)), 0);
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
