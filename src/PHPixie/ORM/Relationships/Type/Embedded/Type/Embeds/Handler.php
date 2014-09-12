<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds;

abstract class Handler extends \PHPixie\ORM\Relationships\Type\Embedded\Handler
{

    public function mapRelationship($side, $query, $group, $plan, $fieldPrefix = null)
    {
        $query->startWhereGroup($group->logic, $group->negated());
        $this->embedsGroupMapper->mapConditions($query, $group->conditions(), $side->itemModel, $plan, $this->getFieldPrefix($fieldPrefix, $side->path));
        $query->endWhereGroup();
    }

    protected function removeItemFromOwner($item)
    {
        $oldOwner = $item->owner();
        if ($oldOwner !== null) {
            $oldOwnerPropertyName = $item->ownerPropertyName();
            $oldOwnerProperty = $oldOwner->$oldOwnerPropertyName;

            if ($oldOwnerProperty instanceof \PHPixie\ORM\Relationships\Type\Embedded\Type\Embedsded\Embeds\Type\One\Property\Item) {
                $oldOwnerProperty->remove();
            } else {
                $oldOwnerProperty->remove($item);
            }
        }

    }

    protected function embeddedModel($config, $document, $owner)
    {
        $model = $this->repositoryRegistry($config->itemModel)->loadModel($document);
        $model->setOwnerProperty($owner, $config->ownerProperty);

        return $model;
    }

    protected function fieldPrefix($oldPrefix, $path)
    {
        if ($oldPrefix === null)
            return $path;

        if ($path === null)
            return $oldPrefix;

        return $oldPrefix.'.'.$path;
    }

    public function preload($side, $ownerLoader, $plan)
    {
        $loader = $this->relationshipType->loader($side->config, $ownerLoader);

        return $this->relationshipType->preloader($side, $loader);
    }
}
