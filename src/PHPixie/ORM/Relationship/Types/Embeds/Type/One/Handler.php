<?php

namespace PHPixie\ORM\Relationships\Types\Embeds\Type\One;

class Handler extends PHPixie\ORM\Relationships\Types\Embeds\Handler {
    public function getEmbeddedModel($embedConfig, $owner)
    {
        $path = $embedConfig->path;
        $document = $this->getDocument($model->data()->document(), $this->explodePath($path));
        
        if ($document === null)
            return null;
        
        return $this->embeddedModel($embedConfig, $owner);
    }
    
    public function createEmbeddedModel($embedConfig, $owner)
    {
        list($parent, $key) = $this->getParentAndKey($owner, $embedConfig->path, true);
        $document = $this->planners->document()->addDocument($parent, $key);
        return $this->embeddedModel($embedConfig, $document);
    }
    
    public function setEmbeddedModel($embedConfig, $owner, $item)
    {
        $this->checkEmbeddedClass($embedConfig, $item);
        list($parent, $key) = $this->getParentAndKey($owner, $embedConfig->path, true);
        $this->planners->document()->setDocument($parent, $key, $embeddedModel->data()->document());
    }
    
    public function removeEmbedded($embedConfig, $owner)
    {
        $documentPlanner = $this->planners->document();
        list($parent, $key) = $this->getParentAndKey($owner, $embedConfig->path);
        if ($parent !== null && $documentPlanner->documentExists($parent, $key))
            $documentPlanner->removeDocument($parent, $key);
    }
}