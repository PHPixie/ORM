<?php

namespace PHPixie\ORM\Loaders\Loader;

abstract class Embedded extends \PHPixie\ORM\Loaders\Loader
{
    protected $embeddedModel;
    
    public function __construct($loaders, $embeddedModel)
    {
        parent::__construct($loaders);
        $this->embeddedModel = $embeddedModel;;
    }
    
    protected function loadEntity($document)
    {
        return $this->embeddedModel->loadEntity($document);
    }

}
