<?php

namespace PHPixie\ORM\Model\Data\Data\Document;

class Builder
{
    public function document($dataOject = null)
    {
        return new Type\Document($this, $dataOject);
    }

    public function documentArray($array = array())
    {
        return new Type\DocumentArray($this, $array);
    }
    
    public function arrayIterator($documentArray)
    {
        return new Type\DocumentArray\Iterator($documentArray);
    }
    
    public function diff($set, $unset)
    {
        return new Diff($set, $unset);
    }
}
