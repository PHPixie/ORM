<?php

class Push {

	protected $update_query;
	protected $items = array();
	protected $path;
	protected $result_steps = array();
	
	public function __construct($update_query, $path) {
		$this->update_query = $update_query;
		$this->path = $path;
	}
	
	public function add_result_step($step) {
		$this->result_steps[] = $step;
	}
	
	public function add_item($item) {
		$this->items[] = $item;
	}
	
	
	public function execute() {
		$data = $this->items;
		
		foreach($this->result_steps as $step)
			foreach($step->result() as $item)
				$data[] = $item;
		
		$this->update_query
						->data(array(
							'$pushAll' => array(
								$this->path,
								$data
							)
						))
						->execute();
	}
}