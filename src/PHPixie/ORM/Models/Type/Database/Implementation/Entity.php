<?php

namespace PHPixie\ORM\Models\Type\Database\Implementation;

abstract class Entity extends \PHPixie\ORM\Models\Model\Implementation\Entity
                      implements \PHPixie\ORM\Models\Type\Database\Entity
{
    protected $repository;
    protected $isDeleted = false;
    protected $isNew;
    
    public function __construct($entityPropertyMap, $repository, $data, $isNew = false)
    {
        $this->repository = $repository;
        $this->isNew = $isNew;
        
        parent::__construct($entityPropertyMap, $repository->config(), $data);
    }
    
    public function isNew()
    {
        return $this->isNew;
    }
    
    public function setIsNew($isNew)
    {
        $this->isNew = $isNew;
        return $this;
    }
    
    public function isDeleted()
    {
        return $this->isDeleted;
    }
    
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }
    
    public function id()
    {
        $this->assertNotDeleted();
        return $this->getField($this->config->idField);
    }
    
    public function setId($id) {
        $this->assertNotDeleted();
        $this->setField($this->config->idField, $id);
        return $this;
    }
    
    public function save()
    {
        $this->repository->save($this);
        return $this;
    }
    
    public function delete()
    {
        $this->repository->delete($this);
        return $this;
    }
    
    public function getRelationshipProperty($name, $createMissing = true)
    {
        $this->assertNotDeleted();
        return parent::getRelationshipProperty($name, $createMissing);
    }
    
    protected function assertNotDeleted()
    {
        if ($this->isDeleted())
            throw new \PHPixie\ORM\Exception\Entity("This entity has been deleted.");
    }

}