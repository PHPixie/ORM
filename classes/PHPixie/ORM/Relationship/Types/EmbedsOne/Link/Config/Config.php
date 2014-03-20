<?php

namespace PHPixie\ORM\Relationships\Types\Embeds\Side\Config\Embedded;

class Config {

    public $propertyName;
    public $model;
    public $type;
    protected $map;
    
    public function __construct($embeddedConfig, $inflector, $propertyName, $config)
    {
        $this->propertyName = $propertyName;
        $this->type = $config->get('type', 'single');
        
        if ($this->type !== 'single' && $this->type !== 'multiple')
            throw new \PHPixie\ORM\Exception\Mapper("Embed type must be either 'single' or 'multiple'");
        
        if(($embeddedModel = $config->get('model')) === null) {
            if($this->type === 'single'){
                $embeddedModel = $propertyName;
            }else{
                $embeddedModel = $inflector->singular($propertyName);
            }
        }
        
        $this->embeddedModel = $embeddedModel;
        $this->map = $embeddedConfig->map($config->slice('embeds'));
    }
}