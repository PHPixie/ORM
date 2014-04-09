<?php

namespace PHPixe\ORM\Relationships\Embeds;

class Handler extends \PHPixie\ORM\Relationship\Type\Handler
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
    
    public function getEmbedded($model, $embedConfig)
    {
        $path = getPath($model, $embedConfig);
        $document = $this->getDocument($model->data()->document(), $this->explodePath($path));
        
        if ($document === null)
            return null;
        
        return $this->embeddedModel($embedConfig, $document, $path);
    }
    
    public function createEmbedded($model, $embedConfig)
    {
        $path = getPath($model, $embedConfig);
        list($parent, $key) = $this->getParentAndKey($model, $path, true);
        $document = $this->planners->document()->add($parent, $key);
        return $this->embeddedModel($embedConfig, $document, $path);
        
    }
    
    public function setEmbedded($model, $embedConfig, $embeddedModel)
    {
		$ownerProperty = $embedConfig->ownerProperty;
		if ($embeddedModel->$ownerProperty() !== null) {
			$embeddedModel->$ownerProperty->set(null);
		}
		
        $path = getPath($model, $embedConfig);
        $this->checkEmbeddedClass($embedConfig, $embeddedModel);
        list($parent, $key) = $this->getParentAndKey($model, $path, true);
        $this->planners->document()->set($parent, $key, $embeddedModel->data()->document());
    }
    
    public function removeEmbedded($model, $embedConfig)
    {
        $path = getPath($model, $embedConfig);
        $documentPlanner = $this->planners->document();
        list($parent, $key) = $this->getParentAndKey($model, $path);
        if ($parent !== null && $documentPlanner->exists($parent, $key))
            $documentPlanner->remove($parent, $key);
    }
    
    public function arrayAddEmbedded($model, $embedConfig, $key = null)
    {
        $path = getPath($model, $embedConfig);
        $array = $this->getArray($model, $path, true);
        $document = $this->planners->document()->arrayAddDocument($array, $key);
        return $this->embeddedModel($embedConfig, $document, $path.'.'.$key);
    }

    public function arrayGetEmbedded($model, $embedConfig, $key)
    {
        $path = getPath($model, $embedConfig);
        $array = $this->getArray($model, $path);
        if ($array === null)
            return null;
        
        $document = $this->planners->document()->arrayGet($array, $key);
        return $this->embeddedModel($embedConfig, $document, $path.'.'.$key);
    }
    
    public function arrayExistsEmbedded($model, $embedConfig, $key)
    {
        $path = getPath($model, $embedConfig);
        $array = $this->getArray($model, $path);
        if ($array === null)
            return false;
        
        return $this->planners->document()->arrayExists($array, $key);
    }
    
    public function arraySetEmbedded($model, $embedConfig, $key, $embeddedModel)
    {
        $path = getPath($model, $embedConfig);
        $this->checkEmbeddedClass($embedConfig, $embeddedModel);
        $array = $this->getArray($model, $path, true);
        $this->planners->document()->arraySet($array, $key, $embeddedModel->data()->document());
    }
    
    public function arreyUnsetEmbedded($model, $embedConfig, $key)
    {
        $path = getPath($model, $embedConfig);
        $array = $this->getArray($model, $path);
        if($array !== null)
            $this->planners->document()->arrayUnset($array, $key);
    }
    
    public function arrayCountEmbedded($model, $embedConfig)
    {
        $path = getPath($model, $embedConfig);
        $array = $this->getArray($model, $path);
        if ($array !== null)
            return 0;
        return $this->planners->document()->arrayCount($array);
    }
    
    public function arrayClearEmbedded($model, $embedConfig)
    {
        $path = getPath($model, $embedConfig);
        $array = $this->getArray($model, $path);
        if ($array !== null)
            $this->planners->document()->arrayClear($array);
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
        if ($model instanceof Model)
            $path = $model->path().'.'.$path;
            
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
    
    protected function getArray($model, $path, $createMissing = false)
    {
        $documentPlanner = $this->planners->document();
        list($parent, $key) = $this->getParentAndKey($model, $path, $createMissing);
        if ($parent === null)
            return null;
        
        $array = $documentPlanner->getArray($parent, $key);
        if ($array === null){
            if(!$createMissing)
                return null;
            $array = $documentPlanner->addArray($parent, $key);
        }
        
        return $array;
    }
    
}
