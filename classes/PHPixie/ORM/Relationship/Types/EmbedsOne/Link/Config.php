<?php

namespace PHPixie\ORM\Relationships\Types\Embeds\Side;

class Config extends PHPixie\ORM\Relationship\Side\Config
{
    public $model;
    protected $map;

    public function __construct($embeddedConfig, $inflector, $config)
    {
        $this->processConfig($embeddedConfig, $config, $inflector);
    }
    
    protected function processConfig($embeddedConfig, $config, $inflector)
    {
        $this->model = $config->get('model');
        $this->map = $embeddedConfig->map($config->slice('embeds'));
    }
}
