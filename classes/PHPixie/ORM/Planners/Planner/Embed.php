<?php

namespace \PHPixie\ORM\Query\Plan\Planner;

class Embed {

	public function push_to($owner, $relationship, $item_collection, $item_repository, $plan) {
		$push_step = $this->steps->push($this->update_query($owner, $ower_repository), $item_repository->path());
		$pull_step = $this->steps->pull($this->update_query($owner, $ower_repository), $item_repository->path(), $item_repository->id_field());
		
		foreach($item_collection->added_models() as $item) {
			$push_step->add_item($item->full_data());
			$pull_step->add_id($item->id());
		}
		
		foreach($item_collection->added_quesries() as $query) {
			$result_plan = $query->map();
			$result_step->peek();
			$push_step->add_result_step($result_step);
			$pull_step->add_result_step($result_step);
			$plan->prepend_plan($result_plan);
		}
		
		$plan->push($pull_step);
		$plan->push($push_step);
	}
	
	protected function update_query($owner, $owner_repository) {
		return $owner_repository
						->query('update')
						->where($onwer_repository->id_field(), $owner->id());
	}
}