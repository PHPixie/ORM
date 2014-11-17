<?php

namespace PHPixieTests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Mapper
 */
class MapperTest extends \PHPixieTests\AbstractORMTest
{
    protected $loaders;
    protected $repositories;
    protected $groupMapper;
    protected $cascadeMapper;
    
    public function setUp()
    {
        $this->loaders = $this->quickMock('\PHPixie\ORM\Loaders');
        $this->repositories = $this->quickMock('\PHPixie\ORM\Repositories');
        $this->groupMapper = $this->quickMock('\PHPixie\ORM\Mapper\Group');
        $this->cascadeMapper = $this->quickMock('\PHPixie\ORM\Mapper\Cascade');
    }
    
    public function testDelete()
    {
        $query = $this->getQuery();
        
    }
    
    protected function getQuery()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Query');
    }
}