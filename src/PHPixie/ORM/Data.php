<?php

namespace PHPixie\ORM;

class Data {
    /**
     * @type \PHPixie\ORM\Data\Types\Document\Builder
     */
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
        return new \PHPixie\ORM\Data\Types\Map($this, $data);
    }
    
    public function document($documentNode)
    {
        return new \PHPixie\ORM\Data\Types\Document($documentNode);
    }
    
    public function diffableDocument($documentNode)
    {
        return new \PHPixie\ORM\Data\Types\Document\Diffable($this, $documentNode);
    }
    
    public function documentFromData($data = null)
    {
        $document = $this->documentBuilder()->document($data);
        return $this->document($document);
    }
    
    public function diffableDocumentFromData($data = null)
    {
        $document = $this->documentBuilder()->document($data);
        return $this->diffableDocument($document);
    }
    
    protected function documentBuilder()
    {
        if ($this->documentBuilder === null) {
            $this->documentBuilder = $this->buildDocumentBuilder();
        }
        
        return $this->documentBuilder;
    }
    
    protected function buildDocumentBuilder()
    {
        return new \PHPixie\ORM\Data\Types\Document\Builder();
    }
}