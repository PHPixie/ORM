<?php

namespace PHPixie\ORM;

class Relationships
{
    protected $ormBuilder;
    protected $relationships = array();
    protected $embeddedGroupMapper;

    public function __construct($ormBuilder)
    {
        $this->ormBuilder = $ormBuilder;
    }

    public function get($name)
    {
        if (!array_key_exists($name, $this->relationships)) {
            $methodName = $name.'Relationship';
            $this->relationships[$name] = $this->$methodName();
        }

        return $this->relationships[$name];
    }

    protected function embeddedGroupMapper()
    {
        if ($this->embeddedGroupMapper === null)
            $this->embeddedGroupMapper = $this->buildEmbeddedGroupMapper();

        return $this->embeddedGroupMapper;
    }

    protected function buildEmbeddedGroupMapper()
    {
        $relationshipMap = $this->ormBuilder->relationshipMap();

        return new Relationships\Types\Embedded\Mapper\Group($this->ormBuilder, $relationshipMap);
    }

    protected function oneToOneRelationship()
    {
        return new Relationships\Relationship\OneToOne($this->ormBuilder);
    }

    protected function oneToManyRelationship()
    {
        return new Relationships\Relationship\OneToMany($this->ormBuilder);
    }

    protected function manyToManyRelationship()
    {
        return new Relationships\Relationship\ManyToMany($this->ormBuilder);
    }

    protected function embedsOneRelationship()
    {
        return new Relationships\Relationship\EmbedsOne($this->ormBuilder, $this->embeddedGroupMapper());
    }

    protected function embedsManyRelationship()
    {
        return new Relationships\Relationship\EmbedsMany($this->ormBuilder, $this->embeddedGroupMapper());
    }

}
