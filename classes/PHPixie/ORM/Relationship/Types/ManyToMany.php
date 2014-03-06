<?php

namespace PHPixie\ORM\Relationships\Types;

class ManyToMany extends PHPixie\ORM\Relationship\Type
{
    public function config($config)
    {
        return new ManyToMany\Side\Config($config);
    }

    public function link($type, $config)
    {
        return new ManyToMany\Link($this, $type, $config);
    }

    public function buildHandler()
    {
        return new ManyToMany\Handler();
    }

    protected function links($config)
    {
        return array('left', 'right');
    }

}
