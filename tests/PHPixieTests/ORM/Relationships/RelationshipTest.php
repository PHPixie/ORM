<?php

namespace PHPixieTests\ORM\Relationships;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship
 */
abstract class RelationshipTest extends \PHPixieTests\AbstractORMTest
{
    protected $relationship;
    protected $ormBuilder;
    
    public function setUp()
    {
        $this->ormBuilder = $this->quickMock('\PHPixie\ORM\Builder');
        $this->relationship = $this->getRelationship();
    }
    
    public function testGetSides()
    {
    
    }
    
    abstract protected function getRelationship();
}