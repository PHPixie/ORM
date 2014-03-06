<?php

namespace PHPixie\ORM\Relationships\Types;

class OneToMany extends PHPixie\ORM\Relationship\Type
{
    public function config($config)
    {
        return new OneToMany\Side\Config($config);
    }

    public function link($type, $config)
    {
        return new OneToMany\Link($this, $type, $config);
    }

    public function buildHandler()
    {
        return new OneToMany\Handler();
    }

    protected function links($config)
    {
        return array('owner', 'items');
    }

}
