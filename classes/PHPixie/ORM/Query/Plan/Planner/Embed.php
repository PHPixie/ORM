<?php

namespace \PHPixie\ORM\Query\Plan\Planner;

class Embed {

	public function move_to($owner, $relationship, $item_collection, $item_repository, $plan) {
		$add_query->data(array(
						'$pushAll' => array(
							$item_repository->embed_path(),
							$item_collection->data()
						)
					));
		$add_step = $this->steps->query($add_query);
		$ids = $item_collection->field($item_repository->id_field());
		
		$plan->push($this->planner->query()->);
	}
}