<?php

namespace PHPixieTests\ORM\Relationships\Relationship\Implementation\Preloader\Result\Multiple;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result\Multiple\IdMap
 */
abstract class IdMapTest extends \PHPixieTests\ORM\Relationships\Relationship\Implementation\Preloader\Result\MultipleTest
{
    protected $loaders;
        
    protected $map = array(
        '1' => array(3, 4, 5),
        '2' => array(6, 7),
        '3' => array()
    );
    
    public function setUp()
    {
        $this->loaders = $this->quickMock('\PHPixie\ORM\Loaders');
        for($i=1; $i<4; $i++)
            $this->entities[$i] = $this->getEntity();
        
        for($i=3; $i<8; $i++)
            $this->preloadedEntities[$i] = $this->getEntity();
        
        parent::setUp();
    }
    
    protected function getExpectedValue($id)
    {
        $ids = $this->map[$id];
        $loader = $this->prepareMultiplePreloader($ids);
        
        $editable = $this->quickMock('\PHPixie\ORM\Loaders\Loader\Proxy\Editable');
        $this->method($this->loaders, 'editableProxy', $editable, array($loader), 1);
        
        return $editable;
    }
    
    protected function getRepository()
    {
        return $this->getDatabaseRepository();
    }
}