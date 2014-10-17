<?php

namespace PHPixie\ORM\Relationships\Type\Embedded;

abstract class Handler extends \PHPixie\ORM\Relationships\Relationship\Handler
{

    protected $embeddedGroupMapper;
    
    public function __construct(
        $ormBuilder,
        $repositories,
        $planners,
        $plans,
        $steps,
        $loaders,
        $relationship,
        $groupMapper,
        $cascadeMapper,
        $embeddedGroupMapper
    ) {
        parent::__construct(
            $ormBuilder,
            $repositories,
            $planners,
            $plans,
            $steps,
            $loaders,
            $relationship,
            $groupMapper,
            $cascadeMapper,
        );
        
        $this->embeddedGroupMapper = $embeddedGroupMapper;
    }
    
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
        $explodedPath = $this->explodePath($path);
        $key = array_pop($explodedPath);
        $document = $this->getDocumentByExplodedPath($model, $explodedPath, $createMissing);
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
