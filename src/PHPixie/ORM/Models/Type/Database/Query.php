<?php

namespace PHPixie\ORM\Models\Type\Database;

interface Query extends \PHPixie\ORM\Conditions\Condition\Collection\Item
{
    public function modelName();
    
    public function limit($limit);
    public function getLimit();
    public function clearLimit();
    
    public function offset($offset);
    public function getOffset();
    public function clearOffset();
    
    public function orderAscendingBy($field);
    public function orderDescendingBy($field);
    public function getOrderBy();
    public function clearOrderBy();
    
    
    public function planFind($preload = array());
    public function find($preload = array());
    public function findOne($preload = array());
    
    public function planDelete();
    public function delete();
    
    public function planUpdate($data);
    public function update($data);
    
    public function planCount();
    public function count();
    

    
    
    public function __get($name);
    
}
