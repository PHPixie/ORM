<?php

namespace \PHPixie\ORM\Relationships\OneToMany\Handler;

class Side {
	
	protected function add_relationship_condition($side, $config, $query, $plan, $condition) {
		$required_model = $config["{$side}_model"];
		$query_field = $side === 'owner' ? $config['item_key'] : $config['owner_repo']->id_field();
		$model_field = $side === 'owner' ? $config['owner_repo']->id_field() : $config['item_key'];
		
		if ($condition->value instanceof \PHPixie\ORM\Model) {
			$model = $condition->value;
			
			if ($model->model_name() != $required_model)
				throw new \PHPixie\ORM\Exception\Mapper("Expected '{$required_model}' model, but '{$model->model_name()}' model was passed.");
			
			if ($model->loaded())
				throw new \PHPixie\ORM\Exception\Mapper("Only saved models can be used.");
				
			$query->get_builder('where')
									->add_operator_condition(
										$condition->logic(), $condition->negated(), 
										$query_field, '=', $model->get($model_field)
									);
			
		}elseif($condition->value instanceof \PHPixie\ORM\Query) {
		
			if ($condition->value->model_name() != $required_model)
				throw new \PHPixie\ORM\Exception\Mapper("Expected '{$required_model}' model query, but a query for the '{$subquery->model_name()}' model was passed.");
				
			$subplan = $this->mapper->map($condition->value);
			$subquery = $subplan->pop()->query;
			$subquery->fields(array($model_field));
			$plan->prepend_plan($subplan);
			$this->subquery_strategy($config)->add_condition($query, $condition->logic, $condition->negated(), $query_field, $subquery);
		}else
			throw new \PHPixie\ORM\Exception\Mapper("Only queries and models can be used");
	}
	
}