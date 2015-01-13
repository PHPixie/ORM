<?php

namespace PHPixie\ORM\Wrappers\Type\Database;

class Entity extends \PHPixie\ORM\Wrappers\Model\Entity
             implements \PHPixie\ORM\Models\Type\Database\Entity
{
    public function id()
    {
        return $this->entity->id();
    }
    
    public function setId($id)
    {
        $this->entity->setId($id);
        return $this;
    }
    
    public function isDeleted()
    {
        return $this->entity->isDeleted();
    }
    
    public function setIsDeleted($isDeleted)
    {
        $this->entity->setIsDeleted($isDeleted);
        return $this;
    }
    
    public function isNew()
    {
        return $this->entity->isNew();
    }
    
    public function setIsNew($isNew)
    {
        $this->entity->setIsNew($isNew);
        return $this;
    }
    
    public function save()
    {
        $this->entity->save();
        return $this;
    }
    
    public function delete()
    {
        $this->entity->delete();
        return $this;
    }
}