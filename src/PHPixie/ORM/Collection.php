<?php

namespace PHPixie\ORM

class Collection implements \IteratorAggregate
{
    protected $added = array();
    protected $removed = array();
    
    public function add($model)
    {
        $id = $model->id();
        if (array_key_exists($id, $this->removed))
            unset($this->removed[$id]);
        $this->added[$id] = $model;
    }
    
    public function remove($model)
    {
        $id = $model->id();
        if (array_key_exists($id, $this->added))
            unset($this->added[$id]);
        $this->removed[$id] = true;
    }
}
