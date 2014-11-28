<?php

namespace PHPixie\ORM\Mappers;

class Update
{
    public function map($updateDatabaseQuery, $update)
    {
        $set = array();
        $remove = array();
        $increments = array();
        
        foreach($update->updates() as $field => $value) {
            if($value instanceof \PHPixie\ORM\Values\Update\Remove) {
                $remove[] = $field;
                
            }elseif($value instanceof \PHPixie\ORM\Values\Update\Increment) {
                $increments[$field] = $value->amount();
                
            }else{
                
                $set[$field] = $value;
            }
        }
        
        $updateDatabaseQuery->set($set);
        
        if(!empty($increments)) {
            if(!($updateDatabaseQuery instanceof \PHPixie\Database\Query\Type\Update\Incrementable))
                throw new \PHPixie\ORM\Exception\Mapper("Database query does not support increments");
            
            $updateDatabaseQuery->increment($increments);
        }
        
        if(!empty($remove)) {
            if(!($updateDatabaseQuery instanceof \PHPixie\Database\Query\Type\Update\Unsetable))
                throw new \PHPixie\ORM\Exception\Mapper("Database query does not support unsetting fields");
            
            $updateDatabaseQuery->_unset($remove);
        }
        
        
    }
    
    
}