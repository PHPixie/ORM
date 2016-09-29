<?php

namespace PHPixie\ORM\Relationships\Type\ManyToMany;

class Handler extends \PHPixie\ORM\Relationships\Relationship\Implementation\Handler
                    implements \PHPixie\ORM\Relationships\Relationship\Handler\Mapping\Database,
                               \PHPixie\ORM\Relationships\Relationship\Handler\Preloading
{
    public function query($side, $related)
    {
        $config = $side->config();
        $side = $side->type();
        $entity = $config->get($side.'Model');
        $property = $config->get($side.'Property');
        $repository = $this->getRepository($entity);
        return $repository->query()->relatedTo($property, $related);
    }

    public function loadProperty($side, $entity)
    {
        $config = $side->config();
        $loader = $this->query($side, $entity)->find();
        $editable = $this->loaders->editableProxy($loader);
        
        $opposing = $this->getOpposing($side->type());
        
        $property = $entity->getRelationshipProperty($config->{$opposing.'Property'});
        $property->setValue($editable);
    }

    public function linkPlan($config, $leftItems, $rightItems)
    {
        $plan = $this->plans->steps();
        list($leftSide, $rightSide) = $this->plannerSides($config, $leftItems, $rightItems);
        $pivot = $this->plannerPivot($config);
        $this->planners->pivot()->link($pivot, $leftSide, $rightSide, $plan);

        return $plan;
    }

    public function unlinkPlan($config, $leftItems, $rightItems)
    {
        $plan = $this->plans->steps();
        list($leftSide, $rightSide) = $this->plannerSides($config, $leftItems, $rightItems);
        $pivot = $this->plannerPivot($config);
        $this->planners->pivot()->unlink($pivot, $leftSide, $rightSide, $plan);

        return $plan;
    }

    public function unlinkAllPlan($side, $items)
    {
        $config = $side->config();
        $plan = $this->plans->steps();
        $opposing = $this->getOpposing($side->type());
        $plannerSide = $this->plannerSide($config, $opposing, $items);
        $pivot = $this->plannerPivot($config);
        $this->planners->pivot()->unlinkAll($pivot, $plannerSide, $plan);

        return $plan;
    }

    protected function plannerSides($config, $leftItems, $rightItems)
    {
        $sides = array();
        foreach (array('left', 'right') as $side) {
            $items = $side === 'left' ? $leftItems : $rightItems;
            $sides[] = $this->plannerSide($config, $side, $items);
        }

        return $sides;
    }

    protected function plannerSide($config, $side, $items)
    {
        $entity = $config->get($side.'Model');
        return $this->planners->pivot()->side(
                                            $items,
                                            $this->getRepository($entity),
                                            $config->get($side.'PivotKey')
                                        );
    }

    protected function plannerPivot($config)
    {
        $pivotPlanner = $this->planners->pivot();

        if ($config->pivotConnection !== null) {
            return $pivotPlanner->pivotByConnectionName($config->pivotConnection, $config->pivot);
        }

        $connection = $this->getRepository($config->leftModel)->connection();
        return $pivotPlanner->pivot($connection, $config->pivot);
    }

    public function mapDatabaseQuery($query, $side, $collectionCondition, $plan)
    {
        $dependencies   = $this->getMappingDependencies($side);
        $config         = $dependencies['config'];
        $sideRepository = $dependencies['sideRepository'];
        $inPlanner      = $this->planners->in();

        $sideIdField = $this->getIdField($sideRepository);

        $sideQuery = $sideRepository->databaseSelectQuery();
        $this->mappers->conditions()->map(
            $sideQuery,
            $sideRepository->modelName(),
            $collectionCondition->conditions(),
            $plan
        );


        $pivotQuery = $dependencies['pivot']->databaseSelectQuery();
        $inPlanner->subquery(
            $pivotQuery,
            $config->get($dependencies['type'].'PivotKey'),
            $sideQuery,
            $sideIdField,
            $plan
        );

        $inPlanner->subquery(
            $query,
            $this->getIdField($dependencies['opposingRepository']),
            $pivotQuery,
            $config->get($dependencies['opposing'].'PivotKey'),
            $plan,
            $collectionCondition->logic(),
            $collectionCondition->isNegated()
        );
    }

    public function mapPreload($side, $preloadProperty, $result, $plan, $relatedLoader)
    {

        $dependencies   = $this->getMappingDependencies($side);
        $config         = $dependencies['config'];
        $sideRepository = $dependencies['sideRepository'];
        $inPlanner      = $this->planners->in();

        $pivotQuery = $dependencies['pivot']->databaseSelectQuery();

        $inPlanner->result(
                            $pivotQuery,
                            $config->get($dependencies['opposing'].'PivotKey'),
                            $result,
                            $this->getIdField($dependencies['opposingRepository']),
                            $plan
                        );

        $pivotResult = $this->steps->reusableResult($pivotQuery);
        $plan->add($pivotResult);

        $sideQuery = $sideRepository->databaseSelectQuery();

        $inPlanner->result(
                            $sideQuery,
                            $this->getIdField($sideRepository),
                            $pivotResult,
                            $config->get($dependencies['type'].'PivotKey'),
                            $plan
                        );
        $preload = $preloadProperty->preload();
        $options = $preloadProperty->options();
        
        if(isset($options['queryCallback'])) {
            $callback = $options['queryCallback'];
            $callback($sideQuery);
        }
        
        $preloadStep = $this->steps->reusableResult($sideQuery);
        $plan->add($preloadStep);
        $loader = $this->loaders->reusableResult($sideRepository, $preloadStep);

        $preloadingProxy = $this->loaders->preloadingProxy($loader);
        $cachingProxy = $this->loaders->cachingProxy($preloadingProxy);

        $this->mappers->preload()->map(
            $preloadingProxy,
            $sideRepository->modelName(),
            $preload,
            $preloadStep,
            $plan,
            $cachingProxy
        );

        return $this->relationship->preloader($side, $sideRepository->config(), $preloadStep, 
                                              $cachingProxy, $pivotResult);

    }

    protected function getMappingDependencies($side)
    {
        $dependencies = array();

        $type     = $side->type();
        $config   = $side->config();
        $opposing = $this->getOpposing($type);

        return array(
            'config'             => $config,
            'type'               => $type,
            'opposing'           => $opposing,
            'pivot'              => $this->plannerPivot($config),
            'sideRepository'     => $this->getRepository($config->get($type.'Model')),
            'opposingRepository' => $this->getRepository($config->get($opposing.'Model'))
        );

        return $dependencies;
    }

    protected function getOpposing($type)
    {
        return $type === 'left' ? 'right' : 'left';
    }

    public function linkProperties($config, $left, $right)
    {
        $this->processProperties('add', $left, $config->leftProperty, $right);
        $this->processProperties('add', $right, $config->rightProperty, $left);
    }

    public function unlinkProperties($config, $left, $right)
    {
        $this->processProperties('remove', $left, $config->leftProperty, $right);
        $this->processProperties('remove', $right, $config->rightProperty, $left);
    }

    public function unlinkAllProperties($side, $entity)
    {
        $property = $this->getPropertyIfLoaded($entity, $side->propertyName());
        if ($property !== null) {
            $loader = $property->value();
            $items = $loader->accessedEntities();
            $type = $side->type();
            $itemsProperty = $side->config()->get($type.'Property');
            $this->processProperties('remove', $items, $itemsProperty, $entity);
            $loader->removeAll();
        }
    }

    public function resetProperties($side, $items)
    {
        $opposing = $this->getOpposing($side->type());
        $itemsProperty = $side->config()->get($opposing.'Property');

        if(!is_array($items))
            $items = array($items);

        foreach($items as $item) {
            if(!$this->isEntityValue($item))
                continue;
            $property = $this->getPropertyIfLoaded($item, $itemsProperty);
            if($property !== null)
                $property->reset();
        }
    }

    protected function processProperties($action, $owners, $ownerProperty, $items)
    {
        if (!is_array($owners))
            $owners = array($owners);

        if (!is_array($items))
            $items = array($items);

        $resetOwners = false;
        foreach ($items as $item) {
            if (!$this->isEntityValue($item)) {
                $resetOwners = true;
                break;
            }
        }

        foreach ($owners as $owner) {
            if (!$this->isEntityValue($owner))
                continue;

            $property = $this->getPropertyIfLoaded($owner, $ownerProperty);
            if($property === null)
                continue;

            if ($resetOwners) {
                $property->reset();
                continue;
            }

            $loader = $property->value();
            if ($action === 'remove') {
                $loader->remove($items);
            } else {
                $loader->add($items);
            }
        }
    }

    public function handleDelete($side, $result, $plan, $sidePath)
    {
        $config = $side->config();
        $opposing = $this->getOpposing($side->type());
        
        $pivot  = $this->plannerPivot($config);
        $query = $pivot->databaseDeleteQuery();
        $pivotKey = $config->get($opposing.'PivotKey');
        
        $repository = $this->getRepository($side->modelName());
        $idField = $this->getIdField($repository);
        $this->planners->in()->result($query, $pivotKey, $result, $idField, $plan);
        $deleteStep = $this->steps->query($query);
        $plan->add($deleteStep);
    }

    protected function getRepository($modelName)
    {
        return $this->models->database()->repository($modelName);
    }

    protected function getIdField($repository)
    {
        return $repository->config()->idField;
    }

}
