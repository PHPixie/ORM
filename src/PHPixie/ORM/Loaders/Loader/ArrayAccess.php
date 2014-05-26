<?php

namespace PHPixie\ORM\Loaders\Loader;

class ArrayAccess extends \PHPixie\ORM\Loaders\Loader
{
    protected $arrayAccess;

    public function __construct($loaders, $arrayAccess)
    {
        $this->arrayAccess = $arrayAccess;
        parent::__construct($loaders);
    }

    public function offsetExists($offset)
    {
        return $this->arrayAccess->offsetExists($offset);
    }

    public function getByOffset($offset)
    {
        return $this->arrayAccess->offsetGet($offset);
    }
}
