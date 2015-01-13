<?php

namespace PHPixie\ORM\Wrappers\Type\Embedded;

class Entity extends \PHPixie\ORM\Wrappers\Model\Entity
             implements \PHPixie\ORM\Models\Type\Embedded\Entity
{
    public function setOwnerRelationship($owner, $ownerPropertyName)
    {
        $this->entity->setOwnerRelationship($owner, $ownerPropertyName);
        return $this;
    }
    
    public function unsetOwnerRelationship()
    {
        $this->entity->unsetOwnerRelationship();
        return $this;
    }
    
    public function owner()
    {
        return $this->entity->owner();
    }
    
    public function ownerPropertyName()
    {
        return $this->entity->ownerPropertyName();
    }
}