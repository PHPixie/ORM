<?php

namespace PHPixe\ORM\Relationships\Types\ManyToMany;

class Handler extends \PHPixie\ORM\Relationship\Type\Handler
{
    public function query($side, $related)
    {
        $side = $side->config()->getSide($side-> type());
        return $this->registryRepository->get($side['model'])->related($side['property'], $related);
    }

    public function linkPlan($config, $leftItems, $rightItems)
    {
        $plan = $this->orm->plan();
        list($leftSide, $rightSide) = $this->plannerSides($config, $leftItems, $rightItems);
        $pivot = $this->plannerPivot($config);
        $this->planers->pivot()->link($pivot, $leftSide, $rightSide, $plan);
        return $plan;
    }

    public function unlinkPlan($config, $leftItems = null, $rightItems = null)
    {
        $plan = $this->orm->plan();
        list($leftSide, $rightSide) = $this->plannerSides($config, $leftItems, $rightItems);
        $pivot = $this->plannerPivot($config);
        $this->planers->pivot()->unlink($pivot, $leftSide, $rightSide, $plan);
        return $plan;
    }
    
    protected function plannerSides($config, $leftItems, $rightItems)
    {
        $sides = array();
        $pivotPlanner = $this->planners->pivot();
        
        foreach(array('left', 'right') as $side) {
            $model = $config->get($side.'Model');
            $items = $side === 'left' ? $leftItems : $rightItems;
            
            if ($items === null) {
                $sides[] = null;
            }else {
                $sides[] = $pivotPlanner->side(
                                            $items
                                            $this->repositoryRegistry->get($model),
                                            $config->get($side.'PivotKey')
                                        );
            }
        }
        
        return $sides;
    }
    
	protected function pivotConnection($config)
	{
		if ($config->pivotConnection !== null)
			return $this->orm->databaseConnection($config->pivotConnection);
		
        return $this->repositoryRegistry->get($config->leftModel)->connection();
	}
	
    protected function plannerPivot($config)
    {
		$pivotConnection = $this->pivotConnection($config);
        return $this->planners->pivot()->pivot($pivotConnection, $config->pivot);
    }
    
    public function mapRelationship($side, $group, $query, $plan)
    {
        $type = $side->type();
        $config = $side->config();
        
        $opposing = $type === 'left' ? 'right' : 'left';
        
        $pivot = $this->plannerPivot($config);
        $inPlanner = $this->planners->in();

        $sideRepository = $this->repositoryRegistry($config->get($type.'Model'));
        $sideQuery = $sideRepository->dbQuery()->fields(array($sideRepository->idField()));
        $this->groupMapper->mapConditions($sideQuery, $group->conditions(), $sideRepository->modelName(), $plan);
        
        $pivotQuery = $pivot->query();
        $inPlanner->subquery(
                            $pivotQuery,
                            $config->get($type.'PivotKey'),
                            $sideQuery,
                            $sideIdField
                            $plan
                        );

        $opposingRepository = $this->repositoryRegistry($config->get($opposing.'Model'));
        
        $inPlanner->subquery(
                            $query,
                            $opposingRepository->idField(),
                            $pivotQuery,
                            $config->get($opposing.'PivotKey'),
                            $plan,
                            $group->logic(),
                            $group->negated()
                        );
    }

    public function preload($side, $resultLoader, $plan)
    {
        $config = $side->config();
        $side = $side->type();
        $opposing = $side === 'left' ? 'right' : 'left';
        $inPlanner = $this->planners->in();
        
        $pivot = $this->plannerPivot($config);
        $pivotQuery = $pivot->query();
        
        $opposingRepository = $this->repositoryRegistry($config->get($opposing.'Model'));
        
        $inPlanner->loader(
                            $pivotQuery,
                            $config->get($opposing.'PivotKey'),
                            $resultLoader,
                            $opposingRepository->idField()
                            $plan
                        );
        
        $pivotStep = $this->steps->resusableResult($pivotQuery);
        $preloadPlan->push($pivotStep);
        
        $sideRepository = $this->repositoryRegistry($config->get($side.'Model'));
        $query = $sideRepository->dbQuery();
        
        $inPlanner->result(
                            $query,
                            $sideRepository->idField(),
                            $pivotStep,
                            $config->get($side.'PivotKey'),
                            $plan
                        );
        
        $preloadStep = $this->steps->resusableResult($query);
        $preloadPlan->push($preloadStep);
        $loader = $this->loaders->reusableResult($sideRepository, $preloadStep);
        return $this->relationshipType->preloader($side, $loader, $pivotStep);
    }
    
    public function linkProperties($config, $left, $right)
    {
        $this->processProperties('add', $left, $config->leftProperty, $right);
        $this->processProperties('add', $right, $config->rightProperty, $left);
    }
    
    public function unlinkProperties($config, $left, $right)
    {
        $this->processProperties('remove', $left, $config->leftPropert, $right);
        $this->processProperties('remove', $right, $config->rightProperty, $left);
    }
    
    public function resetProperties($side, $items)
    {
        $property = $side->config()->get($side->type().'Property');
        $this->processProperties('reset', $items, $property, array());
    }
    
    protected function addItemsToProperty($action, $owners, $ownerProperty, $items) {
    
        if (!is_array($owners)
            $owners = array($owners);
            
        if (!is_array($items))
            $items = array($items);
        
        if ($action === 'reset') {
            $resetOwners = true;
        }else{
            $resetOwners = false;
            foreach($items as $item) {
                if (!($item instanceof \PHPixie\ORM\Model)) {
                    $resetOwners = true;
                    break;
                }
            }
        }
        
        foreach($owners as $owner) {
            if (!($item instanceof \PHPixie\ORM\Model)) 
                continue;
                
            $property = $owner->relationshipProperty($ownerProperty);
            if ($property === null || !$property->loaded())
                continue;
            
            if ($resetOwners){
                $property->reset();
                continue;
            }
            
            $loader = $property->value();
            if($action === 'remove'){
                $loader->remove($items);
            }else {
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
		$repository = $this->repositoryRegistry->get($modelName);
		$this->planners->in()->result($query, $pivotKey, $resultStep, $repository->idField());
		$deleteStep = $this->steps->query($query);
		$plan->push($deleteStep);
	}
}
