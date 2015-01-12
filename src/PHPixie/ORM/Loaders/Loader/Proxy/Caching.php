<?php

namespace PHPixie\ORM\Loaders\Loader\Proxy;

class Caching extends \PHPixie\ORM\Loaders\Loader\Proxy
{
    protected $entities = array();

    public function getByOffset($offset)
    {
        if(!array_key_exists($offset, $this->entities))
            $this->entities[$offset] = $this->loader->getByOffset($offset);

        return $this->entities[$offset];
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->entities) || $this->loader->offsetExists($offset);
    }

}
