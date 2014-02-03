<?php

namespace PHPixie\ORM\Mapper;

class Group {
	
	protected $optimizer;
	
	public function map($model, $conditions) {
		$conditions = $this->optimizer->optimize($conditions);
		
	}
	
	public function get_query($model, $conditions) {
		
	}
	
	protected function add_conditions($query, $conditions, $plan) {
		$builder = $query->get_builder('where');
		
		foreach($conditions as $cond) {
			
			if ($cond instanceof \PHPixie\ORM\Conditions\Condition\Operator) {
				$builder->add_operator_condition($cond->logic, $cond->negated, $cond->field, $cond->operator, $cond->values);
			
			}elseif($cond instanceof \PHPixie\ORM\Conditions\Condition\Relationship) {
				$this->map_relationship_condition($cond, $current_model, $query, $plan)
				
			}elseif($cond instanceof \PHPixie\ORM\Conditions\Condition\Group\Relationship) {
				$this->map_relationship_group($group, $current_model, $query, $plan);
				
			}elseif($cond instanceof \PHPixie\ORM\Conditions\Condition\Group) {
				$this->map_condition_group();
				
			}else
				throw new \PHPixie\ORM\Exception\Mapper("Unexpected condition encountered");
		}
	}
	
	protected map_condition_group() {
		$builder->start_group_verbose($cond->logic, $cond->negated());
		$this->add_conditions($query, $conditions);
		$builder->end_group();
	}
	
	protected function map_relationship_group($group, $model_name, $query, $plan) {
		$this->get_handler($model_name, $group->relationship)
				->map_relationship_group($group, $model_name, $query, $plan);
	}
	
	protected function map_relationship_condition($relationship, $model_name, $query, $plan) {
		
		if ($relationship->value instanceof \PHPixie\ORM\Query) {
			$this->map_relationship_query($relationship, $model_name, $query, $plan);
		
		}elseif ($relationship->value instanceof \PHPixie\ORM\Model){
			$this->map_model_relationship($relationship, $model_name, $query, $plan);
		
		}
	}
	
	protected function map_relationship_query($relationship, $model_name, $query, $plan) {
		$query = $relationship->value;
		$group = $this->orm->relationship_group($relationship->relationship);
		$group->set_conditions($query->get_builder()->get_conditions());
		$this->map_relationship_group($group, $model_name, $query, $plan);
	}
	
	protected function map_model_relationship($relationship, $model_name, $query, $plan) {
		$this->get_handler($model_name, $relationship->relationship)
						->map_model_relationship($relationship, $model_name, $query, $plan);
	}
	
	
}