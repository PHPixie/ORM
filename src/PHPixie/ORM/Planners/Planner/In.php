<?php

namespace PHPixie\ORM\Planners\Planner;

class In extends \PHPixie\ORM\Planners\Planner
{
    protected $conditions;
    protected $mappers;
    protected $steps;
    
    public function __construct($conditions, $mappers, $steps)
    {
        $this->conditions = $conditions;
        $this->mappers = $mappers;
        $this->steps   = $steps;
    }

    public function items($query, $modelName, $items, $plan, $logic = 'and', $negate = false)
    {
        $condition = $this->conditions->in($modelName, $items);
        $condition->setLogic($logic);
        $condition->setIsNegated($negate);
        
        $this->mappers->conditions()->map(
            $query,
            $modelName,
            array($condition),
            $plan
        );
    }
    
    public function itemIds($query, $queryField, $repository, $items, $plan, $logic = 'and', $negate = false)
    {
        $idField = $repository->config()->idField;
        $modelQuery = $repository->query()->in($items);
        
        $this->databaseModelQuery($query, $queryField, $modelQuery, $idField, $plan, $logic, $negate);
    }
    
    public function databaseModelQuery($query, $queryField, $modelQuery, $modelQueryField, $plan, $logic = 'and', $negate = false)
    {
        $queryPlan = $modelQuery->planFind();
        $plan->appendPlan($queryPlan->requiredPlan());
        $subquery = $queryPlan->queryStep()->query();
        
        $this->subquery($query, $queryField, $subquery, $modelQueryField, $plan, $logic, $negate);
        
    }
    
    public function result($query, $queryField, $reusableResult, $resultField, $plan, $logic = 'and', $negate = false)
    {
        $placeholder = $query->addPlaceholder($logic, $negate);
        $inStep = $this->steps->in($placeholder, $queryField, $reusableResult, $resultField);
        $plan->add($inStep);
    }

    public function subquery($query, $queryField, $subquery, $subqueryField, $plan, $logic = 'and', $negate = false)
    {
        $strategy = $this->selectStrategy($query->connection(), $subquery->connection());
        $strategy->in($query, $queryField, $subquery, $subqueryField, $plan, $logic, $negate);
    }
    
    protected function selectStrategy($queryConnection, $subqueryConnection)
    {
        if (!($queryConnection instanceof \PHPixie\Database\Type\SQL\Connection)) {
            return $this->strategy('multiquery');
        }
        
        if ($queryConnection !== $subqueryConnection) {
            return $this->strategy('multiquery');
        }
        
        return $this->strategy('subquery');
    }
    
    protected function buildSubqueryStrategy()
    {
        return new \PHPixie\ORM\Planners\Planner\In\Strategy\Subquery();
    }
    
    protected function buildMultiqueryStrategy()
    {
        return new \PHPixie\ORM\Planners\Planner\In\Strategy\Multiquery(
            $this->steps
        );
    }
}
