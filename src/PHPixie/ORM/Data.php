<?php

namespace PHPixie\ORM;

class Data {
    
    public function diff($set)
    {
        return new \PHPixie\ORM\Data\Diff($set);    
    }
    
    public function removingDiff($set, $unset)
    {
        return new \PHPixie\ORM\Data\Diff\Removing($set, $unset);
    }
}