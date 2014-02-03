<?php

namespace \PHPixie\ORM\Relationships\ManyToMany\Handler\Side;

class Right {
	
	protected $side = 'left';
	
	public function add_left_side_conditions($group, $right_handler, $pivot_handler, $config, $query, $plan) {
		$this->add_opposing_side_conditions($group, 'right', $left_handler, $pivot_handler, $config, $query, $plan);
	}
	
}