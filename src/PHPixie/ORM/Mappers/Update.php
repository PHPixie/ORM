<?php

namespace PHPixie\ORM\Mappers;

class Update
{
    public function map($updateDatabaseQuery, $update)
    {
        $set = array();
        $unset = array();
        $increments = array();
        
        foreach($update->updates() as $field => $value) {
            if($value instanceof \PHPixie\ORM\Values\Update\UnsetField) {
                $unset[] = $field;
                
            }elseif($value instanceof \PHPixie\ORM\Values\Update\Increment) {
                $increments[$field] = $value->amount();
                
            }else{
                
                $set[$field] = $value;
            }
        }
        
        if(!empty($increments)) {
            if($updateDatabaseQuery isntanceof \PHPixie\Database\Query
        }
        $this->mapIncrement($updateDatabaseQuery, $increments);
        
        $updateDatabaseQuery->set($set);
    }
    
    
}