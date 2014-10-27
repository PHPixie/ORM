<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Preloader;

abstract class Loader extends \PHPixie\ORM\Loaders\Loader
{
    protected $config;
    protected $ownerLoader;
    protected $offsets;

    public function __construct($loaders, $config, $ownerLoader)
    {
        parent::__construct($loaders);
        $this->config = $config;
        $this->ownerLoader = $ownerLoader;
    }

    public function offsetExists($offset)
    {
        $this->requireOffsets();
        return array_key_exists($offset, $this->offsets);
    }

    public function getByOffset($offset)
    {
        if(!$this->offsetExists($offset)) {
            throw new \PHPixie\ORM\Exception\Loader("Offset $offset does not exist");
        }

        return $this->getModelByOffset($offset);
    }

    protected function requireOffsets()
    {
        if($this->offsets === null)
            $this->updateOffsets();
    }

    abstract protected function getModelByOffset($offset);
    abstract protected function updateOffsets();
}
