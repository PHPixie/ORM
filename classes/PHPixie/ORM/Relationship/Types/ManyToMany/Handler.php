<?php

namespace PHPixe\ORM\Relationships\Types\ManyToMany;

class Handler extends \PHPixie\ORM\Relationship\Type\Handler
{
    public function query($link, $related)
    {
        $config = $link->config();
        $side = $link->type();

        return $this->buildQuery($config->{"{$side}_model"}, $config->{"{$side}_property"}, $related);
    }

    public function link($link, $items, $opposingItems)
    {
        return $this->modifyLink('link', $link, $items, $opposingItems);
    }

    public function unlink($link, $items, $opposingItems)
    {
        return $this->modifyLink('unlink', $link, $items, $opposingItems);
    }

    public function unlinkAll($link, $items)
    {
        $side = $link->type();
        $config = $link->config();

        $firstSide = $this->getPlannerSide($config, $side, $items);
        $pivot = $this->getPlannerPivot($config);

        $plan = $this->orm->plan();
        $this->planners->pivot()->unlink($pivot, $firstSide, $plan);

        return $plan;
    }

    protected function modifyLink($method, $link, $items, $opposingItems)
    {
        $side = $link->type();
        $config = $link->config();

        $firstSide = $this->getPlannerSide($config, $side, $items);
        $secondSide = $this->getPlannerSide($config, $this->opposingSide($side), $opposingItems);
        $pivot = $this->getPlannerPivot($config);

        $plan = $this->orm->plan();
        $pivotPlanner = $this->planners->pivot();

        if ($method === 'link') {
            $pivotPlanner->link($pivot, $firstSide, $secondSide, $plan);
        } else {
            $pivotPlanner->unlink($pivot, $firstSide, $plan, $secondSide);
        }

        return $plan;
    }

    protected function opposingSide($side)
    {
        if ($side === 'left')
            return 'right';

        if ($side === 'right')
            return 'left';

        throw new \PHPixie\ORM\Exception\Mapper("Side must be either 'left' or 'right', '{$side}' was passed.")
    }

    protected function getPlannerSide($config, $side, $items)
    {
        $model = $config->get("{$side}_model");
        $collection = $this->orm->collection($model);
        $collection->add($items);
        $repository = $this->repositoryRegistry->get($model);
        $pivotKey = $config->get("{$side}_pivot_key");

        return $this->planners->pivot()->side($collection, $repository, $pivotKey);
    }

    protected get_planner_pivot($config) {
        $pivotConnection = $this->db->get($config->pivotConnection);

        return $this->planners->pivot()->pivot($pivotConnection, $config->pivot)
    }

    protected function getSides($config)
    {
        $sides = array();
        foreach (array('left', 'right') as $side) {
            $model = $config->get("{$side}_model");
            $repo = $this->registryRepository->get($model);
            $idField = $repo->idField();
            $pivotKey = $config->get("{$side}_pivot_key");

            $sides[$side] = array(
                'model' => $model,
                'repo' => $repo,
                'id_field' => $idField,
                'pivot_key' => $pivotKey
            );
        }

        return $sides;
    }

    public function mapRelationship($link, $group, $query, $plan)
    {
        $side = $link->type();
        $config = $link->config();
        $opposing = $this->opposingSide($side);
        $sides = $this->getSides($config);
        $pivotConnection = $this->db->get($config->pivotConnection);
        $inPlanner = $this->planners->in();

        $opposingQuery = $sides[$opposing]['repo']->dbQuery()->fields(array($sides[$opposing]['id_field']));
        $pivotQuery = $pivotConnection->query('select')->collection($config->pivot);

        $this->groupMapper->mapConditions($opposingQuery, $group->conditions(), $sides[$opposing]['model'], $plan);

        $inPlanner->query(
                            $pivotQuery,
                            $sides[$opposing]['pivot_key'],
                            $opposingQuery,
                            $sides[$opposing]['id_field'],
                            $plan
                        );

        $inPlanner->query(
                            $query,
                            $sides[$side]['id_field'],
                            $pivotQuery,
                            $sides[$side]['pivot_key'],
                            $plan,
                            $group->logic(),
                            $group->negated()
                        );
    }

    public function preload($link, $loader, $resultStep, $resultPlan)
    {
        $side = $link->type();
        $config = $link->config();
        $opposing = $this->opposingSide($side);
        $sides = $this->getSides($config);
        $pivotConnection = $this->db->get($config->pivotConnection);
        $inPlanner = $this->planners->in();
        $preloadPlan = $resultPlan->preloadPlan();

        $pivotQuery = $pivotConnection->query('select')->collection($config->pivot);
        $placeholder = $query->getWhereBuilder()->addPlaceholder();
        $pivotStep = $this->steps->in($placeholder, $sides[$opposing]['pivot_key'], $resultStep, $sides[$opposing]['id_field']);
        $preloadPlan->push($pivotStep);

        $query = $sides[$side]['repo']->dbQuery();
        $inPlanner->query(
                    $query,
                    $sides[$side]['id_field'],
                    $pivotQuery,
                    $sides[$side]['pivot_key'],
                    $plan
                );

        $preloadStep = $this->steps->result($query);
        $preloadPlan->push($preloadStep);

        return $preloadStep;
    }
}
