<?php

namespace PHPixe\ORM\Relationship\Types\Embedded\Type\Embeds;

abstract class Handler extends \PHPixie\ORM\Relationship\Types\Embedded\Handler
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
    
    
    protected function embeddedModel($embedConfig, $document, $path)
    {
        return $this->relationship->embeddedModel($embedConfig, $document, $path)
    }
    
}
