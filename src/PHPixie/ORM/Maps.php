<?php

namespace PHPixie\ORM;

class Maps
{
    protected $relationships;
    protected $configSlice;
    
    protected $mapsBuilt = false;
    
    protected $relationshipMap;
    protected $entityPropertyMap;
    protected $queryPropertyMap;
    

    public function __construct($relationships, $configSlice)
    {
        $this->relationships = $relationships;
        $this->configSlice = $configSlice;
    }

    public function relationship()
    {
        $this->ensureMaps();
        return $this->relationshipMap;
    }
    
    public function entityProperty()
    {
        $this->ensureMaps();
        return $this->entityPropertyMap;
    }
    
    public function queryProperty()
    {
        $this->ensureMaps();
        return $this->queryPropertyMap;
    }
    
    public function preload()
    {
        $this->ensureMaps();
        return $this->queryPropertyMap;
    }
    
    public function cascadeDelete()
    {
        $this->ensureMaps();
        return $this->queryPropertyMap;
    }
    
    protected function ensureMaps()
    {
        if(!$this->mapsBuilt) {
            $this->buildMaps();
            $this->mapsBuilt = true;
        }
    }
    
    protected function buildMaps()
    {
        $this->entityPropertyMap = $this->buildEntityPropertyMap();
        $this->queryPropertyMap  = $this->buildQueryPropertyMap();
        $this->relationshipMap  = $this->buildRelationshipMap();
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
        if($side instanceof \PHPixie\ORM\Relationships\Relationship\Side\Relationship) {
            $this->relationshipMap->add($side);
        }
        
        if($side instanceof \PHPixie\ORM\Relationships\Relationship\Side\Property\Entity) {
            $this->entityPropertyMap->add($side);
        }
        
        if($side instanceof \PHPixie\ORM\Relationships\Relationship\Side\Property\Query) {
            $this->queryPropertyMap->add($side);
        }
        
        if($side instanceof \PHPixie\ORM\Relationships\Relationship\Side\Preload) {
            $this->preloadMap->add($side);
        }
        
        if($side instanceof \PHPixie\ORM\Relationships\Relationship\Side\Cascade\Delete && $side->isDeleteHandled()) {
            $this->preloadMap->add($side);
        }
    }

    protected function buildRelationshipMap()
    {
        return new Maps\Map\Relationship();
    }
    
    protected function buildEntityPropertyMap()
    {
        return new Maps\Map\Property\Entity($this->relationships);
    }
    
    protected function buildQueryPropertyMap()
    {
        return new Maps\Map\Property\Query($this->relationships);
    }
}
