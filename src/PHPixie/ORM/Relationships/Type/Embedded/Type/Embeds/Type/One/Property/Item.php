<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Property;

class Item extends \PHPixie\ORM\Relationships\Relationship\Property\Model
{
    public function __invoke($createMissing = false)
    {
        if (!$this->loaded)
            $this->reload();

        if ($createMissing && $this->value === null)
            $this->create();

        return $this->value;
    }

    protected function load()
    {
        return $this->handler->getEmbeddedModel($this->config(), $this->model);
    }

    public function create()
    {
        $config = $this->config();
        $this->loaded = true;
        $this->value = $this->handler->createEmbeddedModel($config, $this->model);
        $this->handler->setOwnerProperty($config, $this->value, null);
    }

    public function remove()
    {
        $config = $this->config();
        $this->loaded = true;
        $this->handler->removeEmbeddedModel($config, $this->model);
        $this->handler->setOwnerProperty($config, $this->value, null);
    }

    public function set($model)
    {
        $config = $this->config();
        $this->loaded = true;
        $this->handler->setEmbeddedModel($config, $this->model, $model);
        $this->handler->setOwnerProperty($config, $model, $this->model);
    }
}
