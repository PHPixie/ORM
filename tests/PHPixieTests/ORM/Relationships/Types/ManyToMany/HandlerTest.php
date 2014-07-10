<?php

namespace PHPixieTests\ORM\Relationships\Types\ManyToMany;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Types\ManyToMany\Handler
 */
class HandlerTest extends \PHPixieTests\ORM\Relationships\Relationship\HandlerTest
{
    public function testQuery()
    {
        $side = $this->side('left', array(
            'leftModel'    => 'fairy',
            'leftProperty' => 'flowers'
        ));
        
        $repository = $this->getRepository();
        $this->method($this->repositories, 'get', $repository, array('fairy'), 0);
        
        $query = $this->getQuery();
        $this->method($repository, 'query', $query, array(), 0);
        
        $related = $this->getModel();
        $this->method($query, 'related', $query, array('flowers', $related), 0);
        $this->assertEquals($query, $this->handler->query($side, $related));
    }
    

    protected function getQuery()
    {
        return $this->quickMock('\PHPixie\ORM\Query');
    }
    
    protected function getRepository()
    {
        return $this->abstractMock('\PHPixie\ORM\Repositories\Repository\Database');
    }
    
    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Types\ManyToMany\Side\Config');
    }
    
    protected function getSide()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Types\ManyToMany\Side');
    }
    
    protected function getRelationship()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Types\ManyToMany');
    }
    
    protected function getHandler()
    {
        return new \PHPixie\ORM\Relationships\Types\ManyToMany\Handler(
            $this->ormBuilder,
            $this->relationship,
            $this->repositories,
            $this->planners,
            $this->steps,
            $this->loaders,
            $this->groupMapper,
            $this->cascadeMapper
        );
    }
}