<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Steps;

class RemoveChild
{
    protected $repository;
    protected $config;
    protected $resultStep;

    public function __construct($repository, $config, $resultStep)
    {
        $this->repository = $repository;
        $this->config = $config;
        $this->resultStep = $resultStep;
    }

    public function execute()
    {
        $data = $this->resultStep->getFields(['id', 'rootId', 'left', 'right', 'depth']);
        if(count($data) !== 1) {
            throw new \Exception("");
        }

        $node = $data[0];

        if($node['rootId'] === null) {
            return;
        }

        $offset = $node['right'] - $node['left'] + 1;
        if($offset == 2) {
            $this->updateQuery()
                ->set('left', null)
                ->set('right', null)
                ->set('rootId', null)
                ->set('depth', null)
                ->where('id', $node['id'])
                ->execute();

            $this->move(-$offset, $node['right'], $node['rootId']);
            return;
        }

        $this->updateQuery()
            ->increment('left', 1-$node['left'])
            ->increment('right', 1-$node['left'])
            ->set('rootId', $node['id'])
            ->increment('depth', -$node['depth'])
            ->where('left', '>=', $node['left'])
            ->where('right', '<=', $node['right'])
            ->where('rootId', $node['rootId'])
            ->execute();

        $this->move(-$offset, $node['right'], $node['rootId']);
    }

    public function move($offset, $from, $rootId)
    {
        foreach(array('left', 'right') as $property) {
            $this->updateQuery()
                ->increment($property, $offset)
                ->where($property, '>=', $from)
                ->where('rootId', $rootId)
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
