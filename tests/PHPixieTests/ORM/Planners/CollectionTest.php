<?php

namespace PHPixieTests\ORM\Planners;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Collection
 */
class CollectionTest extends \PHPixieTests\AbstractORMTest
{
    protected $collection;
    protected $items;
    
    public function setUp()
    {
        $this->items = array(
            $this->model(),
            $this->query()
        );
        $this->collection = new \PHPixie\ORM\Planners\Collection('fairy', $this->items);
    }
    
    public function testConstruct()
    {
        
    }
    
    
    protected function model($modelName = 'fairy', $isNew = false, $isDeleted = false)
    {
        $model = $this->abstractMock('\PHPixie\ORM\Model\Repository\Database\Model');
        $this->method($model, 'modelName', $modelName, array());
        $this->method($model, 'isNew', $isNew, array());
        $this->method($model, 'isDeleted', $isDeleted, array());
        return $model;
    }
    
    protected function query($modelName = 'fairy')
    {
        $query = $this->quickMock('\PHPixie\ORM\Query');
        $this->method($query, 'modelName', $modelName, array());
        return $query;
    }
}