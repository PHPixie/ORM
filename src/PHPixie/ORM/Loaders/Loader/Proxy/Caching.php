<?php

namespace PHPixie\ORM\Loaders\Loader\Proxy;

class Caching extends \PHPixie\ORM\Loaders\Loader\Proxy
{
    protected $models = array();

    public function getByOffset($offset)
    {
        if(!array_key_exists($offset, $this->models))
            $this->models[$offset] = $this->loader->getByOffset($offset);

        return $this->models[$offset];
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->models) || $this->loader->offsetExists($offset);
    }

}
