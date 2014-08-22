<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One;

class Handler extends PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Handler
{
    public function setOwnerProperty($config, $item, $owner)
    {
        $this->removeItemFromOwner($item);
        if ($owner !== null) {
            $ownerProperty = $config->ownerProperty;
            $item->setOwnerProperty($owner, $ownerProperty);
            $owner->$ownerProperty->setValue($item);
        }
    }

    public function get($config, $owner)
    {
        $document = $this->getDocument($model->data()->document(), $this->explodePath($config->path));

        if ($document === null)
            return null;

        return $this->embeddedModel($config, $document, $owner);
    }

    public function create($config, $owner)
    {
        list($parent, $key) = $this->getParentAndKey($owner, $config->path, true);
        $document = $this->planners->document()->addDocument($parent, $key);

        return $this->embeddedModel($config, $document, $owner);
    }

    public function set($config, $owner, $item)
    {
        $this->assertModelName($item, $config->itemModel);
        list($parent, $key) = $this->getParentAndKey($owner, $config->path, true);
        $this->planners->document()->setDocument($parent, $key, $model->data()->document());
    }

    public function remove($config, $owner)
    {
        $documentPlanner = $this->planners->document();
        list($parent, $key) = $this->getParentAndKey($owner, $config->path);
        if ($parent !== null && $documentPlanner->documentExists($parent, $key))
            $documentPlanner->removeDocument($parent, $key);
    }
}
