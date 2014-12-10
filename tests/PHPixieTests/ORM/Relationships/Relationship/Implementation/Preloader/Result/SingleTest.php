<?php

namespace PHPixieTests\ORM\Relationships\Relationship\Implementation\Preloader\Result;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Preloader\Result\Single
 */
abstract class SingleTest extends \PHPixieTests\ORM\Relationships\Relationship\Implementation\Preloader\ResultTest
{
    protected $map = array(
        1 => 5,
        2 => 6,
        3 => 7,
        4 => 8,
    );
    
    public function setUp()
    {
        for($i=1; $i<5; $i++)
            $this->entities[$i] = $this->getEntity();
        
        for($i=5; $i<9; $i++)
            $this->preloadedEntities[$i] = $this->getEntity();
        
        parent::setUp();
    }
    
    protected function getExpectedValue($id)
    {
        $id = $this->map[$id];
        return $this->preloadedEntities[$id];
    }
}