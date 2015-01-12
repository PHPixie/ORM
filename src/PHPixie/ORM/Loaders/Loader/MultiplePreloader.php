<?php

namespace PHPixie\ORM\Loaders\Loader;

class MultiplePreloader extends \PHPixie\ORM\Loaders\Loader
{
    protected $multiplePreloader;
    protected $ids;

    public function __construct($loaders, $multiplePreloader, $ids)
    {
        parent::__construct($loaders);
        $this->multiplePreloader = $multiplePreloader;
        $this->ids = $ids;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->ids);
    }

    public function getByOffset($offset)
    {
        return $this->multiplePreloader->getEntity($this->ids[$offset]);
    }
}
