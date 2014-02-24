<?php

namespace \PHPixie\ORM\Query\Plan\Planner;

class In extends \PHPixie\ORM\Query\Plan\Planner{
	
	public function collection($db_query, $query_field, $collection, $collection_field, $plan, $logic, $negated) {
		$collection_connection = $this
									->repository_registry($collection->model_name())
									->connection();
		
		$strategy = $this->select_strategy($db_query->connection(), $collection_connection);
		
		$builder = $db_query->get_builder('where');
		$builder->start_group($logic, $negated);
		
		$ids = $collection->get_field($collection_field);
		if (!empty($ids))
			$builder->_or($field, 'in', $ids);
		
		foreach($collection->added_queries() as $query) {
			$subplan = $query->plan_find();
			$plan->append_plan($subplan->required_plan());
			foreach($subplan->result_plan() as $result_step) {
				$subquery = $result_step->query()
												->fields($collection_field);
				$strategy->add_subquery_condition($builder, $field, $subquery, $collection_field, $plan);
			}
		}
		
		$builder->end_group($logic, $negated);
	}
	
	public function subquery($db_query, $query_field, $subquery, $subquery_field, $plan, $logic, $negated) {
		$strategy = $this->select_strategy($db_query->connection(), $subqery->connection());
		$strategy->add_subquery_condition($builder, $field, $subquery, $collection_field, $plan);

	}

	protected function select_strategy($query_connection, $collection_connection) {
		if ($query_connection instanceof PHPixie\DB\Driver\PDO\Connection && $query_connection === $collection_connection)
			return $this->strategy('condition');
		
		return $this->strategy('multiquery');
	}
	
	protected function build_strategy($name) {
		return $this->planner->in_strategy($name);
	}
}