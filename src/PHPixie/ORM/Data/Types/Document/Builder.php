<?php

namespace PHPixie\ORM\Data\Types\Document;

class Builder
{
    public function arrayIterator($arrayNode)
    {
        return new Node\ArrayNode\Iterator($arrayNode);
    }

    public function document($dataObject = null)
    {
        return new Node\Document($this, $dataObject);
    }

    public function arrayNode($array = array())
    {
        return new Node\ArrayNode($this, $array);
    }
}