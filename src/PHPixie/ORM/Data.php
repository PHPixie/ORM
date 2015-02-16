<?php

namespace PHPixie\ORM;

class Data {
    
    protected $documentBuilder;
    
    public function diff($set)
    {
        return new \PHPixie\ORM\Data\Diff($set);    
    }
    
    public function removingDiff($set, $unset)
    {
        return new \PHPixie\ORM\Data\Diff\Removing($set, $unset);
    }
    
    public function map($data = null)
    {
        return new \PHPixie\ORM\Data\Types\Map($data);
    }
    
    public function document($document = null)
    {
        return new \PHPixie\ORM\Data\Types\Document($document);
    }
    
    public function diffableDocument($data = null)
    {
        return new \PHPixie\ORM\Data\Types\Document\Diffable($data);
    }
    
    public function documentFromData($data = null)
    {
        $document = $this->documentBuilder()->document($data);
        return 
    }
    
    public function documentBuilder()
    {
        if ($this->documentBuilder === null) {
            $this->documentBuilder = $thus->buildDocumentBuilder();
        }
        
        return $this->documentBuilder;
    }
    }
    
    protected function buildDocumentBuilder()
    {
        return \PHPixie\ORM\Data\Document\Builder();
    }
}