<?php

namespace PHPixie\ORM\Query\Plan\Step;

abstract class Cross {
	
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
		$this->keys = array(
			'left' => $keys[0],
			'right' => $keys[1]
		);
	}
	
	public function add_ids($side, $ids) {
		foreach($ids as $id)
			$this->ids[$side][]= $id;
	}
	
	public function add_query($side, $query) {
		$this->id_queries[$side][]= $query;
	}
	
	protected function get_ids($side) {
		$ids = $this->ids[$side];
		foreach($this->id_queries[$side] as $query)
				foreach($query->execute->get_column() as $id)
					$ids[] = $id;
		return $ids;
	}
	
	public abstract function execute();
}