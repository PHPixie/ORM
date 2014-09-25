<?php

namespace PHPixie\ORM\Mapper;

class Group
{
    protected $ormBuilder;
    protected $repositoryRegistry;
    protected $planners;
    protected $optimizer;
    protected $optimizerEnabled = true;

    public function __construct($ormBuilder, $repositoryRegistry, $planners, $optimizer)
    {
        $this->ormBuilder = $ormBuilder;
        $this->repositoryRegistry = $repositoryRegistry;
        $this->planners = $planners;
        $this->optimizer = $optimizer;

    }

    public function mapConditions($dbQuery, $conditions, $modelName, $plan)
    {
        $builder = $query->getBuilder();

        foreach ($conditions as $cond) {

            if($cond instanceof \PHPixie\ORM\Conditions\Condition\Operator) {
                $builder->addOperatorCondition($cond->logic, $cond->negated, $cond->field, $cond->operator, $cond->values);

            }elseif ($cond instanceof \PHPixie\ORM\Conditions\Condition\Collection) {
                $this->mapCollection($cond, $currentModel, $query, $plan);

            }elseif ($cond instanceof \PHPixie\ORM\Conditions\Condition\Group\Relationship) {
                $this->mapRelationshipGroup($group, $currentModel, $query, $plan);

            }elseif ($cond instanceof \PHPixie\ORM\Conditions\Condition\Group) {
                $this->mapConditionGroup($cond, $query, $currentModel, $plan);

            }else
                throw new \PHPixie\ORM\Exception\Mapper("Unexpected condition encountered");
        }
    }

    protected function mapConditionGroup($group, $query, $modelName, $plan)
    {
        $query->startWhereGroup($group->logic, $group->negated());
        $this->mapConditions($query, $group->conditions(), $modelName, $plan);
        $builder->endWhereGroup();
    }

    protected function mapRelationshipGroup($group, $query, $modelName, $plan)
    {
        $side = $this->relationshipMap->getSide($modelName, $group->relationship);
        $handler = $this->ormBuilder->relationshipType($side->relationshipType())->handler();
        $handler->mapRelationship($side, $query, $group, $plan);
    }

    protected function mapCollection($collectionCondition, $dbQuery, $modelName, $plan)
    {
        $idField = $this->repositoryRegistry($modelName)->idField();
        $this->planners->in()->collection(
                                            $dbQuery,
                                            $idField,
                                            $collectionCondition->collection(),
                                            $idField,
                                            $plan,
                                            $collectionCondition->logic,
                                            $collectionCondition->negated()
                                        );
    }

    public function setOptimizerEnabled($enableOptimizer)
    {
        $this->optimizerEnabled = $enableOptimizer;
    }

    public function optimizerEnabled()
    {
        return $this->optimizerEnabled;
    }

}
