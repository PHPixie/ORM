<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Steps;

class AssertSafeDelete extends \PHPixie\ORM\Steps\Step
{
    protected $repository;
    protected $config;
    protected $result;

    protected $ranges;

    public function __construct($repository, $config, $result)
    {
        $this->repository = $repository;
        $this->config = $config;
        $this->result = $result;
    }

    public function execute()
    {
        $fields = array(
            $leftKey = $this->config->leftKey,
            $rightKey = $this->config->rightKey,
            $rootIdKey = $this->config->rootIdKey,
            $depthKey = $this->config->depthKey
        );

        $data = $this->result->getFields($fields);

        usort($data, function($a, $b) use($depthKey, $rootIdKey) {
            if($a[$rootIdKey] == $b[$rootIdKey]) {
                if($a[$depthKey] == $b[$depthKey]) {
                    return 0;
                }

                return $a[$depthKey] < $b[$depthKey] ? 1 : -1;
            }

            return $a[$rootIdKey] > $b[$rootIdKey] ? 1 : -1;
        });

        $subtree = array();
        $last = count($data) - 1;

        $removeRanges = array();

        foreach($data as $key => $row) {
            if(empty($row[$rootIdKey])) {
                continue;
            }

            $subtree[] = $row;

            if($key == $last || $data[$key][$rootIdKey] !== $data[$key+1][$rootIdKey]) {
                $rootId = $data[$key][$rootIdKey];

                $ranges = $this->validateSubtree($subtree);
                foreach($ranges as $range) {
                    $range[] = $rootId;
                    $removeRanges[] = $range;
                }

                $subtree = array();
            }
        }

        foreach($removeRanges as $range) {
            foreach(array($leftKey, $rightKey) as $property) {
                $this->updateQuery()
                    ->increment($property, $range[1])
                    ->where($property, '>=', $range[0])
                    ->where($this->config->rootIdKey, $range[2])
                    ->execute();
            }
        }

    }

    public function validateSubtree($data)
    {
        $leftKey = $this->config->leftKey;
        $rightKey = $this->config->rightKey;

        $this->ranges = array();

        foreach($data as $row) {
            if(!$this->processRange((int) $row[$leftKey], (int) $row[$rightKey])) {
                throw new \PHPixie\ORM\Exception\Relationship("Nodes can only be deleted with all their children");
            }
        }

        $remove = array();

        $count = count($this->ranges);
        for($i=0; $i<$count; $i++) {
            $row = $this->ranges[$i];
            $distance = $row[1] - $row[0] + 1;
            $remove[] = array($row[1], -$distance);

            for($j=$i+1; $j<$count; $j++) {
                $this->ranges[$j][0]-=$distance;
                $this->ranges[$j][1]-=$distance;
            }
        }

        return $remove;
    }

    protected function processRange($left, $right)
    {
        $key = $this->insertRange($left, $right);
        if($left + 1 == $right) {
            return true;
        }

        $currentRight = $left;
        $count = count($this->ranges);

        for($i = $key+1; $i<$count; $i++) {
            $row = $this->ranges[$i];

            if($row[1] > $right || $row[0] != $currentRight + 1) {
                break;
            }

            $currentRight = $row[1];
        }

        if($currentRight + 1 != $right) {
            return false;
        }

        array_splice($this->ranges, $key, $i-$key, array(array($left, $right)));

        return true;
    }

    protected function insertRange($left, $right)
    {
        $insert = array($left, $right);

        foreach($this->ranges as $key => $range) {
            if($range[0] > $left) {
                array_splice($this->ranges, $key, 0, array($insert));
                return $key;
            }
        }

        $this->ranges[] = $insert;
        return count($this->ranges) - 1;
    }

    public function updateQuery()
    {
        return $this->repository->databaseUpdateQuery();
    }

    public function usedConnections()
    {
        return array(
            $this->repository->connection()
        );
    }
}
