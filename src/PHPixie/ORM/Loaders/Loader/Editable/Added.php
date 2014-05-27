<?php

namespace PHPixie\ORM\Loaders\Loader\Editable;

class Added
{
    protected $models = array();
    protected $idsOffsets = array();
    protected $offsetsIds = array();
    
    public function add($model)
    {
        $offset = count($this->models);

    }
    
    public function remove($id)
    {
        $offset = $this->idsOffsets[$id];
        unset($this->idsOffsets[$id]);
        unset($this->offsetsIds[$offset]);
        array_splice($this->models, $offset, 1);
    }
    
    public function offsetExists($offset)
    {
        if(!array_key_exists($offset, $this->models))
            return false;
        
        $model = $this->models[$offset];
        if($model->isDeleted()) {
            $this->remove($this->offsetsIds[$offset]);
            return $this->offsetExists($offset);
        }
        
        return true;        
    }
    
    public function getByOffset($offset)
    {
        if(!$this->offsetExists($offset))
            throw new \PHPixie\ORM\Exception\Loader("Offset $offset does not exist");
        
        return $this->models[$offset];
    }
}