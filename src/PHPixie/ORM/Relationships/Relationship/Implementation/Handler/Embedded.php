<?php

namespace PHPixie\ORM\Relationships\Relationship\Implementation\Handler;

abstract class Embedded extends \PHPixie\ORM\Relationships\Relationship\Implementation\Handler
{

    protected $embeddedModel;
    
    public function __construct($repositories, $planners, $plans, $steps, $loaders, $mappers, $relationship, $embeddedModel)
    {
        $this->embeddedModel = $embeddedModel;
        parent::__construct($repositories, $planners, $plans, $steps, $loaders, $mappers, $relationship);
    }
        
    protected function explodePath($path)
    {
        return explode('.', $path);
    }
    
    protected function getDocument($model, $path, $createMissing = true)
    {
        $explodedPath = $this->explodePath($path);
        return $this->getDocumentByExplodedPath($model, $explodedPath, $createMissing);
    }
    protected function getArrayNode($model, $path, $createMissing = true)
    {
        list($document, $key) = $this->getParentDocumentAndKey($model, $path);
        if($document === null)
            return null;
        $property = $document->get($key);
        if($property !== null) {
            if(!($property instanceof \PHPixie\ORM\Data\Types\Document\Node\ArrayNode))
                throw new \PHPixie\ORM\Exception\Relationship("$path is not an array node");
        }elseif($createMissing) {
            $document->addArray($key);
        }else{
            return null;
        }
        return $document->get($key);
    }
    protected function getParentDocumentAndKey($model, $path, $createMissing = true)
    {
        $explodedPath = $this->explodePath($path);
        $key = array_pop($explodedPath);
        $document = $this->getDocumentByExplodedPath($model, $explodedPath, $createMissing);
        return array($document, $key);
    }
    protected function getDocumentByExplodedPath($model, $explodedPath, $createMissing = true)
    {
        $document = $model->data()->document();
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