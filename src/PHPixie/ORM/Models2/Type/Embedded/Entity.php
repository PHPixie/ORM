<?php

namespace PHPixie\ORM\Repositories\Type\Embedded;

abstract class Model extends \PHPixie\ORM\Model
{
    protected $owner;
    protected $ownerPropertyName;

    public function setOwnerRelationship($owner, $ownerPropertyName)
    {
        $this->owner = $owner;
        $this->ownerPropertyName = $ownerPropertyName;
    }
    
    public function unsetOwnerRelationship()
    {
        $this->owner = null;
        $this->ownerPropertyName = null;
    }

    public function owner()
    {
        return $this->owner;
    }

    public function ownerPropertyName()
    {
        return $this->ownerPropertyName;
    }
}
