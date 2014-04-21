<?php

namespace PHPixie\ORM\Relationships\Types\OneTo\Type\One;

class Handler extends \PHPixie\ORM\Relationships\Types\OneTo\Handler
{
    public function linkPlan($config, $owner, $item)
    {
        $plan = $this->unlinkPlan($config, $owner);
        $plan->appendPlan(parent::linkPlan($config, $owner, $item);

        return $plan;
    }

    public function setItemOwner($config, $item, $owner)
    {
        $this->linkProperty($item, $config->itemProperty, $config->ownerProperty, $owner);
        $this->linkProperty($owner, $config->ownerProperty, $config->itemProperty, $item);
    }

    protected function linkProperty($model, $propertyName, $opposingPropertyName, $newValue)
    {
        if (!($model instanceof \PHPixie\ORM\Model))
            return null;

        $property = $model->$propertyName;

        if ($property->loaded()) {
            $opposingProperty = $property->value->$opposingPropertyName;
            $opposingProperty->setValue(null);
        }

        if ($newValue instanceof \PHPixie\ORM\Query) {
            $property->reset();
        }else
            $property->setValue($newValue);
    }
}
