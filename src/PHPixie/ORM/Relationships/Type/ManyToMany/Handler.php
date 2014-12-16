<?php

namespace PHPixie\ORM\Relationships\Type\ManyToMany;

class Handler extends \PHPixie\ORM\Relationships\Relationship\Implementation\Handler
                    implements \PHPixie\ORM\Relationships\Relationship\Handler\Database\Mapping,
                               \PHPixie\ORM\Relationships\Relationship\Handler\Database\Preloading
{
    protected $database;
    
    public function __construct($repositories, $planners, $plans, $steps, $loaders, $mappers, $database, $relationship)
    {
        parent::__construct($repositories, $planners, $plans, $steps, $loaders, $mappers, $relationship);
        $this->database = $database;
    }
    
    public function query($side, $related)
    {
        $config = $side->config();
        $side = $side->type();
        $entity = $config->get($side.'Model');
        $property = $config->get($side.'Property');
        $repository = $this->repositories->get($entity);
        return $repository->query()->relatedTo($property, $related);
    }

    public function loadProperty($side, $entity)
    {
        $loader = $this->query($side, $entity)->find();
        $editable = $this->loaders->editableProxy($loader);
        return $editable;
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
        $plannerSide = $this->plannerSide($config, $side->type(), $items);
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
                                            $this->repositories->get($entity),
                                            $config->get($side.'PivotKey')
                                        );
    }

    protected function pivotConnection($config)
    {
        if ($config->pivotConnection !== null)
            return $this->ormBuilder->databaseConnection($config->pivotConnection);

        return $this->repositories->get($config->leftModel)->connection();
    }

    protected function plannerPivot($config)
    {
        $pivotConnection = $this->pivotConnection($config);

        return $this->planners->pivot()->pivot($pivotConnection, $config->pivot);
    }

    public function mapQuery($side, $group, $query, $plan)
    {
        $dependencies   = $this->getMappingDependencies($side);
        $config         = $dependencies['config'];
        $sideRepository = $dependencies['sideRepository'];
        $inPlanner      = $this->planners->in();

        $sideIdField = $this->getIdField($sideRepository);

        $sideQuery = $sideRepository->databaseSelectQuery()->fields(array($sideIdField));
        $this->mappers->group()->mapDatabaseQuery(
            $sideQuery,
            $sideRepository->modelName(),
            $group->conditions(),
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
            $group->logic(),
            $group->negated()
        );
    }

    public function mapPreload($side, $preloadProperty, $result, $plan)
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

        $preloadStep = $this->steps->reusableResult($sideQuery);
        $plan->add($preloadStep);
        $loader = $this->loaders->reusableResult($sideRepository, $preloadStep);
        
        $preloadingProxy = $this->loaders->preloadingProxy($loader);
        $cachingProxy = $this->loaders->cachingProxy($preloadingProxy);
        
        $this->mappers->preload()->map(
            $preloadingProxy,
            $sideRepository->modelName(),
            $preloadProperty->preload(),
            $preloadStep,
            $plan
        );
        
        return $this->relationship->preloader($side, $cachingProxy, $pivotResult);

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
            'sideRepository'     => $this->repositories->get($config->get($type.'Model')),
            'opposingRepository' => $this->repositories->get($config->get($opposing.'Model'))
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
            $items = $loader->accessedModels();
            $opposing = $this->getOpposing($side->type());
            $itemsProperty = $side->config()->get($opposing.'Property');
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
            if($item instanceof \PHPixie\ORM\Models\Type\Database\Query)
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
            if (!($item instanceof \PHPixie\ORM\Models\Type\Database\Entity)) {
                $resetOwners = true;
                break;
            }
        }

        foreach ($owners as $owner) {
            if (!($owner instanceof \PHPixie\ORM\Models\Type\Database\Entity))
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


    public function handleDeletion($modelName, $side, $resultStep, $plan)
    {
        $config = $side->config();
        $query = $this->pivotConnection($config)->query('delete');
        $this->planners->query()->setSource($query, $config->pivot);
        $pivotKey = $config->get($side-> type().'PivotKey');
        $repository = $this->repositories->get($modelName);
        $idField = $this->getIdField($repository);
        $this->planners->in()->result($query, $pivotKey, $resultStep, $idField);
        $deleteStep = $this->steps->query($query);
        $plan->push($deleteStep);
    }
                                   
    protected function getIdField($repository)
    {
        return $repository->config()->idField;
    }

}
