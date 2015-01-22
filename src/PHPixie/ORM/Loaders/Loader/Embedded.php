<?php

namespace PHPixie\ORM\Loaders\Loader;

abstract class Embedded extends \PHPixie\ORM\Loaders\Loader
{
    protected $embeddedModel;
    protected $modelName;
    
    public function __construct($loaders, $embeddedModel, $modelName)
    {
        parent::__construct($loaders);
        
        $this->embeddedModel = $embeddedModel;
        $this->modelName     = $modelName;
    }
    
    protected function loadEntity($document)
    {
        return $this->embeddedModel->loadEntity($this->modelName, $document);
    }

}
