<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet;

abstract class Preloader extends \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result
{
    protected $loaders;
    protected $parentResult;

    protected $rootIds = array();

    public function __construct($loaders, $side, $modelConfig, $result, $loader, $parentResult)
    {
        parent::__construct($side, $modelConfig, $result, $loader);
        $this->loaders = $loaders;
        $this->parentResult = $parentResult;
    }

    protected function mapItems()
    {
        $sideConfig = $this->side->config();

        $idField  = $this->modelConfig->idField;
        $leftKey  = $sideConfig->leftKey;
        $rightKey = $sideConfig->rightKey;
        $rootIdKey = $sideConfig->rootIdKey;

        $fields = array($idField, $leftKey, $rightKey, $rootIdKey, 'name');
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
    }

    protected function mapTree($data)
    {
        $sideConfig = $this->side->config();

        $idField  = $this->modelConfig->idField;
        $leftKey  = $sideConfig->leftKey;
        $rightKey = $sideConfig->rightKey;

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
            }else{
                $this->rootIds[]= $lastId;
            }

            if($itemData[$rightKey] - $itemData[$leftKey] > 1) {
                $currentRight = $itemData[$rightKey];
                $stack[$lastId] = $currentRight;
            }
        }
    }

    abstract protected function pushToMap($parentId, $childId);
}
