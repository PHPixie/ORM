<?php

namespace \PHPixie\ORM\Relationships\ManyToMany\Handler\Side;

class Right {
	
	protected $side = 'right';
	
	public function add_left_side_conditions($group, $left_handler, $pivot_handler, $config, $query, $plan) {
		$this->add_opposing_side_conditions($group, 'left', $left_handler, $pivot_handler, $config, $query, $plan);
	}
	
}