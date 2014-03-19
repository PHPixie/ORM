<?php

namespace PHPixie\ORM\Relationships\Types;

class Embedded extends PHPixie\ORM\Relationship\Type
{
    public function config($config)
    {
        return new Embedded\Side\Config($config);
    }

    public function link($type, $config)
    {
        return new Embedded\Link($this, $type, $config);
    }

    public function buildHandler()
    {
        return new Embedded\Handler();
    }

    protected function links($config)
    {
        return array('owner', 'items');
    }

}
