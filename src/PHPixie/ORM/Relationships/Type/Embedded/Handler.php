<?php

namespace PHPixie\ORM\Relationships\Type\Embedded;

abstract class Handler extends \PHPixie\ORM\Relationships\Relationship\Handler
{

    protected function explodePath($path)
    {
        return explode('.', $path);
    }
    
    protected function getDocument($model, $path, $createMissing = true)
    {
        $explodedPath = $this->explodePath();
        return $this->getDocumentByExplodedPath($model, $explodedPath, $createMissing);
    }
    
    protected function getArrayNode($model, $path, $createMissing = true)
    {
        $explodedPath = $this->explodePath();
        $key = array_pop($explodedPath);
        $document = $this->getDocumentByExplodedPath($model, $explodedPath, $createMissing);
        
        if($document === null)
            return null;
        
        if(property_exists($document, $key)) {
            if(!($document->$key instanceof \PHPixie\ORM\Data\Types\Document\Node\ArrayNode))
                throw new \PHPixie\ORM\Exception\Relationship("$path is not an array node");
        }elseif($createMissing) {
            $document->addArray($key);
        }else{
            return null;
        }
        
        return $document->$key;
    }
    
    protected function getDocumentByExplodedPath($model, $explodedPath, $createMissing = true)
    {
        $document = $model->data()->document();
        $last = count($explodedPath) - 1;
        foreach($explodedPath as $i => $step) {
            if(property_exists($document, $key)) {
                if($i === $last && !($document->$key instanceof \PHPixie\ORM\Data\Types\Document\Node\Document)) {
                    $path = implode('.', array_slice($explodedPath, 0, $i+1));
                    throw new \PHPixie\ORM\Exception\Relationship("$path is not a document node.");
                }
            }elseif($createMissing) {
                $document->addDocument($key);
            }else{
                return null;
            }
            
            $document = $document->$key;
        }
        
        return $document;
    }

}
