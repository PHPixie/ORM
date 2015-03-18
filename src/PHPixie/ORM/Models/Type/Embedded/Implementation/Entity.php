<?php

namespace PHPixie\ORM\Models\Type\Embedded\Implementation;

class Entity extends \PHPixie\ORM\Models\Model\Implementation\Entity
                      implements \PHPixie\ORM\Models\Type\Embedded\Entity
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
        return $this;
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
