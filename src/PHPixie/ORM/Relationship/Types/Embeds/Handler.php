<?php

namespace PHPixe\ORM\Relationships\Embeds;

abstract class Handler extends \PHPixie\ORM\Relationship\Type\Handler
{
    public function setOwnerProperty($embedConfig, $item, $owner)
    {
        $itemPropertyName = $embedConfig->ownerProperty;
        $ownerPropertyName = $embedConfig->name;
        
        $itemProperty = $item->$itemPropertyName;
        $oldOwner = $itemProperty->value();
        
        if ($oldOwner !== null) {
            $oldOwnerProperty = $oldOwner->$ownerPropertyName;
            if ($oldOwnerProperty instanceof Embeds\Property\Model\EmbeddedArray) {
                $oldOwner->$ownerPropertyName->remove($item);
            }else {
                $this->unsetOwnerProperty($embedConfig, $item);
            }
        }
        
        $itemProperty->setValue($owner);
    }
    
    public function unsetOwnerProperty($embedConfig, $item)
    {
        $itemPropertyName = $embedConfig->ownerProperty;
        $item->$itemPropertyName->setValue(null);
    }
    
    
    public function mapRelationship($side, $group, $query, $plan)
    {
        $config = $side->config();
        $this->mapper->mapConditionGroup($group->conditions, $query, $config->embeddedMap());
    }
    
    
    public function arrayLoader($property, $models)
    {
        return $this->relationship->arrayLoader($property, $models);
    }
    
    protected function checkEmbeddedClass($embedConfig, $embeddedModel)
    {
        if (!($embeddedModel instanceof $embedConfig->modelClass))
            throw new \PHPixie\ORM\Exception\Handler("Only isntances of '{$embedConfig->modelClass}' can be used for this relationship.");
    }
    
    protected function getPath($model, $embedConfig)
    {
        $path = $embedConfig->path;
        return $path;
    }
    
    protected function getParentAndKey($model, $path, $createMissing = false)
    {
        $path = $this->explodePath($path);
        $key = array_pop($path);
        $parent = $this->getDocument($model->data()->document(), $path, $createMissing);
        return array($parent, $key);
    }
    
    protected function explodePath($path)
    {
        return explode('.', $path);
    }
    
    protected function embeddedModel($embedConfig, $document, $path)
    {
        return $this->relationship->embeddedModel($embedConfig, $document, $path)
    }
    
    protected function getDocument($document, $exploadedPath, $createMissing = false)
    {
        $documentPlanner = $this->planners->document();
        $current = $document;
        
        foreach($exploadedPath as $step) {
            $next = $documentPlanner->getDocument($current, $step);
            if ($next === null) {
                if (!$createMissing)
                    return null;
                $next = $documentPlanner->addDocument$current, $step);
            }
            
            $current = $next;
        }
        
        return $current;
    }
    

    
}
