<?php

namespace PHPixie\ORM\Relationships;

class Maps
{
    protected $relationships;
    protected $configSlice;
    
    protected $mapsBuilt = false;
    
    protected $entityMap;
    protected $queryMap;
    

    public function __construct($relationships, $configSlice)
    {
        $this->relationships = $relationships;
        $this->configSlice = $configSlice;
    }

    public function entity()
    {
        return $this->entitySides;
    }
    
    public function query()
    {
        return $this->querySides;
    }
    
    protected function ensureMaps()
    {
        if($this->mapsBuilt) {
            $this->buildMaps();
            $this->mapsBuilt = true;
        }
    }
    
    protected function buildMaps()
    {
        $this->entityMap = $this->buildEntityMap();
        $this->queryMap = $this->buildQueryMap();
        $this->addSidesFromConfig($this->configSlice);
    }
    
    protected function addSidesFromConfig($configSlice)
    {
        foreach ($configSlice->keys() as $key) {
            $relationshipConfig = $configSlice->slice($key);
            $type = $relationshipConfig->getRequired('type');
            $relationship = $this->relationships->get($type);
            $sides = $relationship->getSides($relationshipConfig);
            
            foreach($sides as $side) {
                $this->addSide($side);
            }
        }
    }
    
    protected function addSide($side)
    {
        $this->entitySides->add($side);
        if($side instanceof \PHPixie\ORM\Relationships\Relationship\Side\Database\Query) {
            $this->querySides->add($side);
        }
    }
    
    protected function buildEntityMap()
    {
        new Maps\Map\Entity($this->relationships);
    }
    
    protected function buildQueryMap()
    {
        new Maps\Map\Query($this->relationships);
    }
}
