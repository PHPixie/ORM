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
				
			}elseif($cond instanceof \PHPixie\ORM\Conditions\Condition\Group\Relationship) {
				$repository = $this->registry->get($query->model);
				$handler = $repository->handler($cond->relationship);
				$handler->add_to_queryplan($plan, $cond->logic, $cond->negated, $cond->conditions());
				
			}elseif($cond instanceof \PHPixie\ORM\Conditions\Condition\Group) {
				$logic = ($cond->negated() ? 'not_' : '').$cond->logic;
				$builder->start_group($logic);
				$this->add_conditions($query, $conditions);
				$builder->end_group();
			}else
				throw new \PHPixie\ORM\Exception\Mapper("Unexpected condition encountered");
			
			
		}
	}
	
}