<?php

namespace PHPixie\ORM\Mappers;

class Database
{
    protected $groupMapper;
    
    public function groupMapper()
    {
        if($this->groupMapper === null)
            $this->groupMapper = $this->buildGroupMapper();
        return $this->groupMapper;
    }
    
    public function preloadMapper()
    {
        if($this->preloadMapper === null)
            $this->preloadMapper = $this->buildPreloadMapper();
        return $this->preloadMapper;
    }
    
    protected function buildGroupMapper()
    {
        return new \PHPixie\ORM\Mappers\Database\Group(
            $this->repositories,
            $this->relationships,
        );
    }
    
    protected function buildPreloadMapper()
    {
        return new \PHPixie\ORM\Mappers\Database\Preload(
            $this->relationships
        );
    }
}