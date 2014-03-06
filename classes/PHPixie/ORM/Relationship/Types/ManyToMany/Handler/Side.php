<?php

namespace \PHPixie\ORM\Relationships\OneToMany\Handler;

abstract class Side
{
    protected $side;
    protected $adapter;

    public function __construct($adapter)
    {
        $this->adapter = $adapter;
    }

    public function idsQuery($conditions, $config, $plan)
    {
        $repo = $config["{$this->side}_repo"];

        $query = $repo->connection()->query('select')
                                        ->fields(array($repo->idField()));

        $this->setRepository($query, $repo);
        $this->mapper->addConditions($query, $conditions, $repo->modelName(), $plan);

        return $query;
    }

    protected function addOpposingSideConditions($group, $opposingSide, $opposingHandler, $pivotHandler, $config, $query, $plan)
    {
        $pivotSubquery = $pivotHandler->idsQuery($group->conditions(), $this->side, $opposingSide, $opposingHandler, $config, $plan);
        $this->pivotStrategy($config)->addCondition($query, $group->logic, $group->negated(), $config["{$this->side}_repo"]->idField(), $pivotSubquery);
    }

    protected function addOpposingSideItems()
    {
        $keys = array(
                    $config["{$side}_pivot_key"],
                    $config["{$opposingSide}_pivot_key"]
                );

        $values = array();
        foreach($ids as $id)
            $values[] = array($sideId, $id);

        $this->db->query('insert', $config['pivot_connection']);
                                                            ->target($config['pivot'])
                                                            ->batchData($keys, $values)
                                                            ->execute();
    }

    protected function setRepository($query, $repository)
    {
        return $this->adapter->setRepository($query, $repository);
    }

    protected function pivotStrategy($config)
    {
        return $this->adapter->pivotStrategy($this->side, $config);
    }
}
