<?php

namespace PHPixie\ORM\Loaders\Loader;

class ResultPreloader extends \PHPixie\ORM\Loaders\Loader
{
    protected $resultPreloader;
    protected $ids;

    public function __construct($loaders, $resultPreloader, $ids)
    {
        parent::__construct($loaders);
        $this->resultPreloader = $resultPreloader;
        $this->ids = $ids;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->ids);
    }

    public function getByOffset($offset)
    {
        return $this->resultPreloader->getModel($this->ids[$offset]);
    }
}
