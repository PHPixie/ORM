<?php

namespace PHPixie\ORM\Relationship;

class Map
{
    protected $propertyMap = array();

    public function __construct($orm, $config)
    {
        foreach ($config->data() as $key => $params) {
            $type = $params['type'];
            $relationshipConfig = $this->config->slice($key);
            $relationship = $orm->relationship($type);
            $links = $relationship->getLinks($relationshipConfig);
            foreach($links as $link)
                $this->addLink($link);
        }
    }

    public function addLink($link)
    {
        $modelName = $link->modelName();
        $propertyName = $link->propertyName();

        if (!isset($this->propertyMap[$modelName]))
            $this->propertyMap[$modelName] = array();

        if (isset($this->propertyMap[$modelName][$propertyName]))
            throw new \PHPixie\ORM\Exception\Mapper("Property '$propertyName' on '$modelName' model has already been defined by a different relationship.");

        $this->propertyMap[$model][$property] = $link;
    }

    public function getLink($modelName, $propertyName)
    {
        return $this->propertyMap[$modelName][$propertyName];
    }

}
