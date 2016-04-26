<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Steps;

class RemoveNodes extends \PHPixie\ORM\Steps\Step
{
    protected $repository;
    protected $config;
    protected $result;

    public function __construct($repository, $config, $result)
    {
        $this->repository = $repository;
        $this->config = $config;
        $this->result = $result;
    }

    public function execute()
    {
        $modelConfig = $this->repository->config();
        $config = $this->config;

        $fields = array(
            $idField = $modelConfig->idField,
            $rootIdKey = $config->rootIdKey,
            $leftKey = $config->leftKey,
            $rightKey = $config->rightKey,
            $depthKey = $config->depthKey
        );

        $data = $this->result->getFields($fields);

        usort($data, function($a, $b) use($leftKey, $rootIdKey) {
            if($a[$rootIdKey] == $b[$rootIdKey]) {
                if($a[$leftKey] == $b[$leftKey]) {
                    return 0;
                }

                return $a[$leftKey] > $b[$leftKey] ? 1 : -1;
            }

            return $a[$rootIdKey] > $b[$rootIdKey] ? 1 : -1;
        });

        $count = count($data);
        for($i=0; $i<$count; $i++) {
            $node = $data[$i];
            if(empty($node[$rootIdKey])) {
                continue;
            }

            $offset = $node[$rightKey] - $node[$leftKey] + 1;
            for($j=$i+1; $j < $count; $j++) {
                if($node[$rootIdKey] !== $data[$j][$rootIdKey]) {
                    break;
                }

                $data[$j][$leftKey]-=$offset;
                $data[$j][$rightKey]-=$offset;
            }

            if($offset == 2) {
                $this->updateQuery()
                    ->set($leftKey, null)
                    ->set($rightKey, null)
                    ->set($rootIdKey, null)
                    ->set($depthKey, null)
                    ->where($idField, $node[$idField])
                    ->execute();

            } else {
                $this->updateQuery()
                    ->increment($leftKey, 1-$node[$leftKey])
                    ->increment($rightKey, 1-$node[$leftKey])
                    ->set($rootIdKey, $node[$idField])
                    ->increment($depthKey, -$node[$depthKey])
                    ->where($leftKey, '>=', $node[$leftKey])
                    ->where($rightKey, '<=', $node[$rightKey])
                    ->where($rootIdKey, $node[$rootIdKey])
                    ->execute();
            }
            
            $this->move(-$offset, $node[$rightKey], $node[$rootIdKey]);
        }
    }

    public function move($offset, $from, $rootId)
    {
        $config = $this->config;

        foreach(array($config->leftKey, $config->rightKey) as $property) {
            $this->updateQuery()
                ->increment($property, $offset)
                ->where($property, '>=', $from)
                ->where($config->rootIdKey, $rootId)
                ->execute();
        }
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
