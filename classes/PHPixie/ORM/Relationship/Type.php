<?php

namespace PHPixie\ORM\Relationship;

abstract class Type
{
    public function getLinks($config)
    {
        $config = $this->config($config);
        $sides = array();
        foreach($this->linkTypes($config) as $link)
            $links[] = $this->link($link, $config);

        return $links;
    }

    abstract public function config($config);
    abstract public function side($type, $config);
    abstract public function modelProperty($side, $model);
    abstract public function queryProperty($side, $model);

    abstract protected function sideTypes($config);

}
