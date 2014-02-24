<?php

namespace PHPixie\ORM\Mapper;

class Group {
	
	protected $optimizer;
	
	protected function map_conditions($db_query, $conditions, $model_name, $plan) {
		$builder = $query->get_builder('where');
		
		foreach($conditions as $cond) {
			
			if ($cond instanceof \PHPixie\ORM\Conditions\Condition\Operator) {
				$builder->add_operator_condition($cond->logic, $cond->negated, $cond->field, $cond->operator, $cond->values);
			
			}elseif($cond instanceof \PHPixie\ORM\Conditions\Condition\Collection) {
				$this->map_collection($cond, $current_model, $query, $plan)
				
			}elseif($cond instanceof \PHPixie\ORM\Conditions\Condition\Group\Relationship) {
				$this->map_relationship_group($group, $current_model, $query, $plan);
				
			}elseif($cond instanceof \PHPixie\ORM\Conditions\Condition\Group) {
				$this->map_condition_group($cond, $query, $current_model, $plan);
				
			}else
				throw new \PHPixie\ORM\Exception\Mapper("Unexpected condition encountered");
		}
	}
	
	protected function map_condition_group($group, $query, $model_name, $plan) {
		$query->start_where_group($group->logic, $group->negated());
		$this->map_conditions($query, $group->conditions(), $model_name, $plan);
		$builder->end_where_group();
	}
	
	protected function map_relationship_group($group, $query, $model_name, $plan) {
		$side = $this->relationship_map->get_side($model_name, $group->relationship);
		$handler = $this->orm->relationship_type($side->relationship_type())->handler();
		$handler->map_relationship($side, $query, $group, $plan);
	}

	protected function map_collection($collection_condition, $db_query, $model_name, $plan) {
		$id_field = $this->repository_registry($model_name)->id_field();
		$this->planners->in->collection(
											$db_query,
											$id_field,
											$collection_condition->collection(),
											$id_field,
											$plan,
											$collection_condition->logic,
											$collection_condition->negated()
										);
	}
	

	
	
}