<?php

namespace PHPixie\ORM\Relationships\Types\Embeds\Side\Config\Embedded;

class Config {

    public $name;
    public $type;
    public $ownerProperty;
    public $modelName;
    public $map;
    
    public function __construct($embedded, $inflector, $name, $config, $defaultOwnerProperty)
    {
        $this->name = $name;
        $this->ownerProperty = $config->get('ownerProperty', $defaultOwnerProperty);
        
        $this->type = $config->get('type', 'one');
        
        if ($this->type !== 'one' && $this->type !== 'many')
            throw new \PHPixie\ORM\Exception\Mapper("Embed type must be either 'one' or 'many'");
        
        if(($this->modelName = $config->get('model')) === null) {
            if($this->type === 'one'){
                $this->modelName = $name;
            }else{
                $this->modelName = $inflector->singular($name);
            }
        }
        
        $this->map = $embedded->map($config->slice('embeds'), $this->name);
    }
}