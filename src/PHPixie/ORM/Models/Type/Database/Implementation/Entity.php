<?php

namespace PHPixie\ORM\Models\Type\Database\Implementation;

abstract class Entity extends \PHPixie\ORM\Models\Model\Implementation\Entity
                      implements \PHPixie\ORM\Models\Type\Database\Entity
{
    protected $repository;
    protected $isDeleted = false;
    protected $isNew;

    public function __construct($entityMap, $repository, $data, $isNew = false)
    {
        parent::__construct($entityMap, $repository->config(), $data);
        $this->repository = $repository;
        $this->isNew = $isNew;
    }

    public function isNew()
    {
        return $this->isNew;
    }

    public function setIsNew($isNew)
    {
        $this->isNew = $isNew;
    }
    
    public function isDeleted()
    {
        return $this->isDeleted;
    }

    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    }
    
    public function id()
    {
        $this->assertNotDeleted();
        return $this->getField($this->config->idField);
    }
    
    public function setId($id) {
        $this->assertNotDeleted();
        return $this->setField($this->config->idField, $id);
    }
    
    public function save()
    {
        $this->repository->save($this);
    }
    
    public function delete()
    {
        $this->repository->delete($this);
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