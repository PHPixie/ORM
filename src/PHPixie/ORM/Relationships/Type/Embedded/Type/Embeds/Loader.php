<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds;

abstract class Loader extends \PHPixie\ORM\Loaders\Loader
{
    protected $config;
    protected $ownerLoader;

    public function __construct($loaders, $config, $ownerLoader)
    {
        parent::__construct($loaders);
        $this->config = $config;
        $this->ownerLoader = $ownerLoader;
    }

}
