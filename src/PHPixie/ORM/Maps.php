<?php

namespace PHPixie\ORM;

class Maps
{
    protected $ormBuilder;
    protected $configSlice;
    
    protected $maps = array(
        'relationship',
        'queryProperty',
        'entityProperty',
        'preload',
        'cascadeDelete'
    );
    
    protected $mapInstances = null;
    
    protected $relationshipMap;
    protected $entityPropertyMap;
    protected $queryPropertyMap;
    

    public function __construct($ormBuilder, $configSlice)
    {
        $this->ormBuilder  = $ormBuilder;
        $this->configSlice = $configSlice;
    }
    
    public function relationship()
    {
        return $this->getMap('relationship');
    }
    
    public function entityProperty()
    {
        return $this->getMap('entityProperty');
    }
    
    public function queryProperty()
    {
        return $this->getMap('queryProperty');
    }
    
    public function preload()
    {
        return $this->getMap('preload');
    }
    
    public function cascadeDelete()
    {
        return $this->getMap('cascadeDelete');
    }
    
    protected function buildMaps()
    {
        $this->mapInstances = array();
        
        foreach($this->maps as $name)
        {
            $method = 'build'.ucfirst($name).'Map';
            $this->mapInstances[$name] = $this->$method();
        }
        $this->addSidesFromConfig($this->configSlice);
    }
    
    protected function addSidesFromConfig($configSlice)
    {
        $relationships = $this->ormBuilder->relationships();
        foreach ($configSlice->keys() as $key) {
            $relationshipConfig = $configSlice->slice($key);
            $type = $relationshipConfig->getRequired('type');
            $relationship = $relationships->get($type);
            $sides = $relationship->getSides($relationshipConfig);
            
            foreach($sides as $side) {
                $this->addSide($side);
            }
        }
    }
    
    protected function addSide($side)
    {
        if($side instanceof \PHPixie\ORM\Relationships\Relationship\Side\Relationship) {
            $this->mapInstances['relationship']->add($side);
        }
        
        if($side instanceof \PHPixie\ORM\Relationships\Relationship\Side\Property\Entity) {
            $this->mapInstances['entityProperty']->add($side);
        }
        
        if($side instanceof \PHPixie\ORM\Relationships\Relationship\Side\Property\Query) {
            $this->mapInstances['queryProperty']->add($side);
        }
        
        if($side instanceof \PHPixie\ORM\Relationships\Relationship\Side\Preload) {
            $this->mapInstances['preload']->add($side);
        }
        
        if($side instanceof \PHPixie\ORM\Relationships\Relationship\Side\Cascade\Delete && $side->isDeleteHandled()) {
            var_dump($side->isDeleteHandled());die;
            $this->mapInstances['cascadeDelete']->add($side);
        }
    }
    
    protected function getMap($name)
    {
        if($this->mapInstances === null) {
            $this->buildMaps();
        }
        
        return $this->mapInstances[$name];
    }

    protected function buildRelationshipMap()
    {
        return new Maps\Map\Relationship();
    }
    
    protected function buildEntityPropertyMap()
    {
        return new Maps\Map\Property\Entity(
            $this->ormBuilder->relationships()
        );
    }
    
    protected function buildQueryPropertyMap()
    {
        return new Maps\Map\Property\Query(
            $this->ormBuilder->relationships()
        );
    }
    
    protected function buildPreloadMap()
    {
        return new Maps\Map\Preload();
    }
    
    protected function buildCascadeDeleteMap()
    {
        return new Maps\Map\Cascade\Delete();
    }
}
