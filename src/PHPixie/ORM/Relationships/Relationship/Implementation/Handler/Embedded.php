<?php

namespace PHPixie\ORM\Relationships\Relationship\Implementation\Handler;

abstract class Embedded extends \PHPixie\ORM\Relationships\Relationship\Implementation\Handler
{
    protected function explodePath($path)
    {
        return explode('.', $path);
    }
    
    protected function getDocument($document, $path, $createMissing = true)
    {
        $explodedPath = $this->explodePath($path);
        return $this->getDocumentByExplodedPath($document, $explodedPath, $createMissing);
    }
    
    protected function getArrayNode($document, $path, $createMissing = true)
    {
        list($parent, $key) = $this->getParentDocumentAndKey($document, $path);
        if($document === null)
            return null;
        $property = $parent->get($key);
        if($property !== null) {
            if(!($property instanceof \PHPixie\ORM\Data\Types\Document\Node\ArrayNode))
                throw new \PHPixie\ORM\Exception\Relationship("$path is not an array node");
        }elseif($createMissing) {
            $parent->addArray($key);
        }else{
            return null;
        }
        return $parent->get($key);
    }
    protected function getParentDocumentAndKey($document, $path, $createMissing = true)
    {
        $explodedPath = $this->explodePath($path);
        $key = array_pop($explodedPath);
        $parent = $this->getDocumentByExplodedPath($document, $explodedPath, $createMissing);
        return array($parent, $key);
    }
    protected function getDocumentByExplodedPath($document, $explodedPath, $createMissing = true)
    {
        $last = count($explodedPath) - 1;
        foreach($explodedPath as $i => $key) {
            $property = $document->get($key);
            if($property !== null) {
                if($i === $last && !($property instanceof \PHPixie\ORM\Data\Types\Document\Node\Document)) {
                    $path = implode('.', array_slice($explodedPath, 0, $i+1));
                    throw new \PHPixie\ORM\Exception\Relationship("$path is not a document node.");
                }
            }elseif($createMissing) {
                $document->addDocument($key);
            }else{
                return null;
            }
            $document = $document->get($key);
        }
        return $document;
    }
}