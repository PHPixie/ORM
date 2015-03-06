<?php

namespace PHPixie\ORM\Planners\Planner;

class Document extends \PHPixie\ORM\Planners\Planner
{
    protected function explodePath($path)
    {
        return explode('.', $path);
    }
    
    public function getDocument($document, $path, $createMissing = false)
    {
        $explodedPath = $this->explodePath($path);
        return $this->getDocumentByExplodedPath($document, $explodedPath, $createMissing);
    }
    
    public function getArrayNode($document, $path, $createMissing = false)
    {
        list($parent, $key) = $this->getParentDocumentAndKey($document, $path);
        if($document === null)
            return null;
        
        $property = $parent->get($key);
        if($property !== null) {
            if(!($property instanceof \PHPixie\ORM\Data\Types\Document\Node\ArrayNode))
                throw new \PHPixie\ORM\Exception\Data("$path is not an array node");
        }elseif($createMissing) {
            $parent->addArray($key);
        }else{
            return null;
        }
        return $parent->get($key);
    }
    
    public function getParentDocumentAndKey($document, $path, $createMissing = false)
    {
        $explodedPath = $this->explodePath($path);
        $key = array_pop($explodedPath);
        $parent = $this->getDocumentByExplodedPath($document, $explodedPath, $createMissing);
        return array($parent, $key);
    }
    
    protected function getDocumentByExplodedPath($document, $explodedPath, $createMissing)
    {
        $last = count($explodedPath) - 1;
        foreach($explodedPath as $i => $key) {
            $property = $document->get($key);
            if($property !== null) {
                if($i === $last && !($property instanceof \PHPixie\ORM\Data\Types\Document\Node\Document)) {
                    $path = implode('.', array_slice($explodedPath, 0, $i+1));
                    throw new \PHPixie\ORM\Exception\Data("$path is not a document node.");
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