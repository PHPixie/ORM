<?php

namespace PHPixie\ORM\Relationships\Relationship\Property;

abstract class Model
{
    protected $model;

    protected $loaded = false;
    protected $value;

    public function __construct($handler, $side, $model)
    {
        parent::__construct($handler, $side);
        $this->model = $model;
    }

    public function __invoke()
    {
        return $this->value();
    }

    public function reload()
    {
        $this->loaded = true;
        $this->value = $this->load();

        return $this->loaded;
    }

    public function reset()
    {
        $this->value = null;
        $this->loaded = false;
    }

    public function model()
    {
        return $this->model;
    }

    public function value()
    {
        if ($this->loaded)
            $this->reload();

        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
        $this->loaded = true;
    }

    public function loaded()
    {
        return $this->loaded();
    }

    abstract protected function load();
}
