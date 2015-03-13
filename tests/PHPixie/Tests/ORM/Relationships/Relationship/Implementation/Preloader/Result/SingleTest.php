<?php

namespace PHPixie\Tests\ORM\Relationships\Relationship\Implementation\Preloader\Result;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Preloader\Result\Single
 */
abstract class SingleTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\Preloader\ResultTest
{
    protected $map = array(
        1 => 5,
        2 => 6,
        3 => 7,
        4 => 8,
        5 => null
    );
    
    public function setUp()
    {
        for($i=1; $i<7;$i++)
            $this->entities[$i] = $this->getEntity();
        
        for($i=5; $i<9; $i++)
            $this->preloadedEntities[$i] = $this->getEntity();
        
        parent::setUp();
    }
    
    protected function getExpectedValue($id)
    {
        if(!array_key_exists($id, $this->map) || $this->map[$id] == null) {
            return null;
        }
        
        $id = $this->map[$id];
        return $this->preloadedEntities[$id];
    }
}