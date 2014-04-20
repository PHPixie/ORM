<?php

namespace PHPixie\ORM\Model\Repository\Database\Model

abstract class Model extends \PHPixie\ORM\Model
{
	protected $isDeleted = false;
	
    public function id()
    {
		$this->assertNotDeleted();
        $idField = $this->repository->idField();
        
        if(isset($this->$idField))
            return $this->$idField;
        
        return null;
    }
	
	protected function assertNotDeleted()
	{
		if ($this->isDeleted())
			throw new \PHPixie\ORM\Exception\Model("This model has been deleted.");
	}
	
	public function isDeleted()
    {
        return $this->isDeleted;
    }
    
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    }
	
    public function isNew()
    {
        return $this->isNew;
    }
    
    public function setIsNew($isNew)
    {
        $this->isNew = $isNew;
    }
	
	public function relationshipProperty($name, $createMissing = true)
	{
		$this->assertNotDeleted();
		parent::relationshipProperty($name, $createMissing);
	}
}