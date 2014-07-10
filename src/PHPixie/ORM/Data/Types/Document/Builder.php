<?php

namespace PHPixie\ORM\Data\Types\Document;

class Builder
{
    public function document($dataOject = null)
    {
        return new Node\Document($this, $dataOject);
    }

    public function arrayNode($array = array())
    {
        return new Node\ArrayNode($this, $array);
    }
    
    public function arrayIterator($documentArray)
    {
        return new Node\ArrayNode\Iterator($documentArray);
    }
}