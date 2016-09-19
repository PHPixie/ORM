<?php

namespace PHPixie\ORM;

class Maps
{
    /**
     * @type \PHPixie\ORM\Builder
     */
    protected $ormBuilder;
    protected $configSlice;

    /**
     * @var array
     */
    protected $maps = array(
        'relationship',
        'queryProperty',
        'entityProperty',
        'preload',
        'cascadeDelete'
    );

    /**
     * @var null|array
     */
    protected $mapInstances = null;

    /**
     * @var
     */
    protected $relationshipMap;
    protected $entityPropertyMap;
    protected $queryPropertyMap;

    /**
     * Maps constructor.
     * @param $ormBuilder \PHPixie\ORM\Builder
     * @param $configSlice
     */
    public function __construct($ormBuilder, $configSlice)
    {
        $this->ormBuilder  = $ormBuilder;
        $this->configSlice = $configSlice;
    }

    /**
     * @return Maps\Map\Relationship
     */
    public function relationship()
    {
        return $this->getMap('relationship');
    }

    /**
     * @return Maps\Map\Property\Entity
     */
    public function entityProperty()
    {
        return $this->getMap('entityProperty');
    }

    /**
     * @return Maps\Map\Property\Query
     */
    public function queryProperty()
    {
        return $this->getMap('queryProperty');
    }

    /**
     * @return Maps\Map\Preload
     */
    public function preload()
    {
        return $this->getMap('preload');
    }

    /**
     * @return Maps\Map\Cascade\Delete
     */
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

    /**
     * @param $side \PHPixie\ORM\Relationships\Relationship\Side
     */
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
            $this->mapInstances['cascadeDelete']->add($side);
        }
    }

    /**
     * @param $name string
     * @return mixed
     */
    protected function getMap($name)
    {
        if($this->mapInstances === null) {
            $this->buildMaps();
        }
        
        return $this->mapInstances[$name];
    }

    /**
     * @return Maps\Map\Relationship
     */
    protected function buildRelationshipMap()
    {
        return new Maps\Map\Relationship();
    }

    /**
     * @return Maps\Map\Property\Entity
     */
    protected function buildEntityPropertyMap()
    {
        return new Maps\Map\Property\Entity(
            $this->ormBuilder->relationships()
        );
    }

    /**
     * @return Maps\Map\Property\Query
     */
    protected function buildQueryPropertyMap()
    {
        return new Maps\Map\Property\Query(
            $this->ormBuilder->relationships()
        );
    }

    /**
     * @return Maps\Map\Preload
     */
    protected function buildPreloadMap()
    {
        return new Maps\Map\Preload();
    }

    /**
     * @return Maps\Map\Cascade\Delete
     */
    protected function buildCascadeDeleteMap()
    {
        return new Maps\Map\Cascade\Delete();
    }
}
