<?php

namespace PHPixie\ORM\Relationships\Types\ManyToMany;

class Side extends PHPixie\ORM\Relationship\Side
{
    public function modelName()
    {
        if ($this->type === 'left') {
            return $this->config->leftModel;
		
        return $this->config->rightModel;
    }

    public function propertyName()
    {
        if ($this->type === 'left') {
            return $this->config->leftProperty;
		
        return $this->config->rightProperty;
    }

    public function relationship()
	{
		return 'manyToMany';
	}
}
