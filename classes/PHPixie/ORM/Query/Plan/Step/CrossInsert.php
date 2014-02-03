<?php

namespace PHPixie\ORM\Query\Plan\Step;

class CrossInsert{
	
	protected $query;
	protected $keys;
	
	protected $ids = array(
		'left' => array(),
		'right' => array(),
	);
	
	protected $id_queries = array(
		'left' => array(),
		'right' => array(),
	);
	
	public function __construct($query, $keys) {
		$this->query = $query;
		$this->keys = $keys;
	}
	
	public function add_ids($side, $ids) {
		foreach($ids as $id)
			$this->ids[$side][]= $id;
	}
	
	public function add_query($side, $query) {
		$this->id_queries[$side][]= $query;
	}
	
	public function execute() {
		foreach(array('left', 'right') as $side) {
			$$side[] = $this->ids[$side];
			foreach($this->id_queries[$side] as $query)
				foreach($query->execute-> get_column() as $id)
					$$side[] = $id;
		}
		
		$values = array();
		
		foreach($left as $left_id)
			foreach($right as $right_id)
				$values[] = array($left_id, $right_id);
				
		$this->query
			->batch_insert($this->keys, $values)
			->execute();
	}
}