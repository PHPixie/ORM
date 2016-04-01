<?php

namespace PHPixie\ORM\Relationships\Relationship\Implementation\Property;

abstract class Entity extends \PHPixie\ORM\Relationships\Relationship\Implementation\Property
                              implements \PHPixie\ORM\Relationships\Relationship\Property\Entity
{
    protected $entity;
    protected $value;
    protected $isLoaded = false;

    public function __construct($handler, $side, $entity)
    {
        parent::__construct($handler, $side);
        $this->entity = $entity;
    }

    public function __invoke()
    {
        return $this->value();
    }

    public function reload()
    {
        $this->load();
        return $this->value;
    }

    public function reset()
    {
        $this->value = null;
        $this->isLoaded = false;
    }

    public function entity()
    {
        return $this->entity;
    }

    public function value()
    {
        if ($this->isLoaded === false) {
            $this->reload();
        }
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
        $this->isLoaded = true;
    }

    public function isLoaded()
    {
        return $this->isLoaded;
    }

    public function side()
    {
        return $this->side;
    }

    abstract protected function load();
}