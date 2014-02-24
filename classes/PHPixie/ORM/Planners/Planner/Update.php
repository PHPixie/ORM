<?php

namespace \PHPixie\ORM\Query\Plan\Planner;

class Update extends \PHPixie\ORM\Query\Plan\Planner{
	
	public function plan($orm_query, $data, $plan) {
		$requires_steps = false;
		
		foreach($data as $field => $value) {
			if ($value instanceof Update\Field) {
				$value_field = $value->value_field();
				$value_source = $value-> value_source();
				
				if ($value_source isntanceof \PHPixie\ORM\Model) {
					$data[$field] = $value_source->$value_field;
				}else {
					$requires_steps = true;
					$data[$field] = $this->subquery_field($orm_query, $value_source, $value_field, $plan);
				}
			}
		}
		
		if ($requires_steps) {
			$plan->push($this->steps->update($orm_query, $data));
		}else
			$plan->append_plan($orm_query->update_plan($data));
	}
	
	public function field($value_source, $value_field) {
		$this->planners->build_update_field($value_source, $value_field);
	}
	
	protected function subquery_field($query, $subquery, $subquery_field, $plan) {
		$query_connection = $query->repository()->connection();
		$subquery_connection = $subquery->repository()->connection();
		
		$subplan = $subquery->find_plan();
		$plan->append_plan($subplan->required_plan());
		$field_subquery = $subplan->result_step()->query()->fields(array($value_field));
		
		if ($query_connection instanceof PHPixie\DB\Driver\PDO\Connection && $query_connection === $subquery_connection)
			return $field_subquery;
			
		$result_step = $this->steps->result_query($field_subquery);
		$plan->push($result_step);
		return $result_step;
	}
}