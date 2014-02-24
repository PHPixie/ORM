<?php

namespace \PHPixie\ORM\Query\Plan\Planner;

class In extends \PHPixie\ORM\Query\Plan\Planner{
	
	public function collection($logic, $negated, $query, $query_field, $collection, $collection_field, $plan) {
		$query->start_where_group($logic, $negated);
		$collection_connection = $this
							->repository_registry($collection->model_name())
							->connection();
		$method = $this->select_strategy($db_query->connection(), $collection_connection);
		$ids = $collection->get_field($collection_field);
		if (!empty($ids))
			$query->or_where($field, 'in', $ids);
		
		foreach($collection->added_queries() as $query) {
			$subplan = $query->plan_find();
			$plan->append_plan($subplan->required_plan());
			$subquery = $subplan->result_step()->query();
			$strategy->$method('or', false, $query, $query_field, $subquery, $collection_field, $plan);
		}
		
		$query->end_where_group();
	}
	
	public function subquery($logic, $negated, $query, $query_field, $subquery, $subquery_field, $plan) {
		$method = $this->method($db_query->connection(), $subqery->connection());
		$strategy->$method($logic, $negate, $query, $query_field, $subquery, $subquery_field, $plan);
	}
	
	protected function subquery_method($logic, $negate, $query, $query_field, $subquery, $subquery_field, $plan) {
		$subquery->fields(array($subquery_field));
		$query->get_where_builder()->add_operator_condition($logic, $negate, $query_field, 'in', $subquery);
	}
	
	protected function multiquery_method($logic, $negate, $query, $query_field, $subquery, $subquery_field, $plan) {
		$subquery->fields(array($subquery_field));
		$result_step = $this->steps->result($subquery);
		$plan->push($result_step);
		$placeholder = $query->get_where_builder()->add_placeholder($logic, $negate);
		$in_step = $this->steps->in($placeholder, $query_field, $result_step, $subquery_field);
		$plan->push($in_step);
	}
	
	protected function method($query_connection, $subquery_connection) {
		if ($query_connection instanceof PHPixie\DB\Driver\PDO\Connection && $query_connection === $subquery_connection)
			return 'subquery_method';
		return 'multiquery_method';
	}
	
}