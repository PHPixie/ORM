<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet;

class Preloader extends \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result
{
    protected $loaders;
    protected $parentResult;
    protected $relatedLoader;

    protected $childMap = array();
    protected $parentMap = array();
    protected $relatedIdMap = array();

    public function __construct($loaders, $side, $modelConfig, $result, $loader, $parentResult, $relatedLoader)
    {
        parent::__construct($side, $modelConfig, $result, $loader);
        $this->loaders = $loaders;
        $this->parentResult = $parentResult;
        $this->relatedLoader = $relatedLoader;
    }

    public function loadProperty($property)
    {
        $this->ensureMapped();
        $entity = $property->entity();
        $type = $property->side()->type();
        $entityId = $entity->id();

        if($this->side->type() === 'children' && $type === 'children') {
            if(array_key_exists($entityId, $this->childMap)) {
                $ids = $this->childMap[$entityId];
            }else{
                $ids = array();
            }

            $loader = $this->buildLoader($ids);
            $value = $this->loaders->editableProxy($loader);
            $property->setValue($value);
            return;
        }

        if($type === 'parent' && array_key_exists($entityId, $this->parentMap)) {
            $value = $this->parentMap[$entityId];

            if($value !== null) {
                if(array_key_exists($value, $this->idOffsets)) {
                    $value = $this->getEntity($value);
                }else {
                    $value = $this->relatedLoader->getByOffset($this->relatedIdMap[$value]);
                }
            }
            $property->setValue($value);
        }
    }

    protected function getMappedFor($entity)
    {

    }

    protected function pushToMap($parentId, $childId)
    {
        if($this->side->type() === 'children' && $parentId !== null) {
            if(!array_key_exists($parentId, $this->childMap)) {
                $this->childMap[$parentId] = array();
            }
            $this->childMap[$parentId][] = $childId;
        }

        $this->parentMap[$childId] = $parentId;
    }

    protected function buildLoader($ids)
    {
        return $this->loaders->multiplePreloader($this, $ids);
    }

    protected function mapItems()
    {
        $sideConfig = $this->side->config();

        $fields = array(
            $idField  = $this->modelConfig->idField,
            $leftKey  = $sideConfig->leftKey,
            $rightKey = $sideConfig->rightKey,
            $rootIdKey = $sideConfig->rootIdKey,
            $sideConfig->depthKey
        );

        $childData = $this->result->getFields($fields);

        $data = array_merge(
            $this->parentResult->getFields($fields),
            $childData
        );

        usort($data, function($a, $b) use($leftKey, $rootIdKey) {
            if($a[$rootIdKey] == $b[$rootIdKey]) {
                if($a[$leftKey] == $b[$leftKey]) {
                    return 0;
                }

                return $a[$leftKey] > $b[$leftKey] ? 1 : -1;
            }

            return $a[$rootIdKey] > $b[$rootIdKey] ? 1 : -1;
        });

        $subtree = array();
        $last = count($data) - 1;

        foreach($data as $key => $row) {
            $subtree[] = $row;

            if($key == $last || $data[$key][$rootIdKey] !== $data[$key+1][$rootIdKey]) {
                $this->mapTree($subtree);
                $subtree = array();
            }
        }

        $this->mapIdOffsets();
        $this->relatedIdMap = array_flip($this->parentResult->getField($idField));
    }

    protected function mapTree($data)
    {
        $sideConfig = $this->side->config();

        $idField  = $this->modelConfig->idField;
        $leftKey  = $sideConfig->leftKey;
        $rightKey = $sideConfig->rightKey;
        $depthKey = $sideConfig->depthKey;

        $stack = array();
        $currentRight = false;
        $lastId = null;

        foreach ($data as $offset => $itemData) {
            if($offset > 0 && $itemData[$idField] === $lastId) {
                continue;
            }

            while($currentRight !== false && $itemData[$leftKey] > $currentRight) {
                array_pop($stack);
                $currentRight = end($stack);
            }

            end($stack);
            $lastId = $itemData[$idField];
            $parentId = key($stack);

            if($parentId) {
                $this->pushToMap($parentId, $lastId);
            }elseif($itemData[$depthKey] < 1) {
                $this->pushToMap(null, $lastId);
            }

            if($itemData[$rightKey] - $itemData[$leftKey] > 1) {
                $currentRight = $itemData[$rightKey];
                $stack[$lastId] = $currentRight;
            }
        }
    }
}
