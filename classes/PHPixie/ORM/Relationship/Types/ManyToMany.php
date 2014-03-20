<?php

namespace PHPixie\ORM\Relationships\Types;

class ManyToMany extends PHPixie\ORM\Relationship\Type
{
    public function config($config)
    {
        return new ManyToMany\Side\Config($config);
    }

    public function side($type, $config)
    {
        return new ManyToMany\Side($this, $type, $config);
    }

    public function buildHandler()
    {
        return new ManyToMany\Handler();
    }

    protected function sideTypes($config)
    {
        return array('left', 'right');
    }

}
