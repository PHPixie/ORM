<?php

class Push {

	protected $update_query;
	protected $ids = array();
	protected $path;
	protected $result_steps = array();
	protected $id_field;
	
	public function __construct($update_query, $path, $id_field) {
		$this->update_query = $update_query;
		$this->path = $path;
		$this->id_field = $id_field;
	}
	
	public function add_result_step($step) {
		$this->result_steps[] = $step;
	}
	
	public function add_id($id) {
		$this->ids[] = $id;
	}
	
	
	public function execute() {
		$ids = $this->ids;
		
		foreach($this->result_steps as $step)
			foreach($step->result() as $item)
				$ids[] = $item->{$this->id_field};
		
		$this->update_query
						->data(array(
							'$pull' => array(
								$this->path => array(
									$this->id_field => array(
										'in' => $ids
									)
								)
							)
						))
						->execute();
	}
}