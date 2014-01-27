<?php

namespace PHPixie\ORM\Mapper\Group;
use \PHPixie\ORM\Conditions\Condition\Group;


class Optimizer extends \PHPixie\DB\Conditions\Logic\Parser{
	
	protected $orm;
	protected $merger;
	
	public function __construct($orm, $merger) {
		$this->orm = $orm;
		$this->merger = $merger;
	}
	
	protected function normalize($condition) {
		if ($condition instanceof Group) {
			$condition = $this->optimize_group($condition);
		}
		
		return array($condition);
	}
	
	protected function optimize_group($group) {
		$conditions = $group->conditions();
		$conditions = $this->optimize($conditions);
		if (!($group instanceof Group\Relationship) && count($conditions) === 1) {
			$subgroup = current($conditions);
			if ($group->negated())
				$subgroup->negate();
			$subgroup->logic = $group->logic;
			$group = $subgroup;
		}else {
			$group->set_conditions($conditions);
		}
		
		return $group;
	}
	
	protected function merge($left, $right) {
		if (count($right) !== 1) {
			foreach($right as $cond)
				$left[] = $cond;
			return $left;
		}
		
		$right = current($right);
		$target = $this->merger->find_merge_target($left, $right, $this->logic_precedance);
		if ($target === null) {
			$left[] = $right;
			return $left;
		}
		
		$cond = $left[$target];
		$common_relationship = null;
		if ($cond instanceof Group\Relationship && $right instanceof Group\Relationship)
			$common_relationship = $this->merger->common_relationship($cond->relationship, $right->relationship);
		
		
		$optimize_merge = true;
		
		$is_suitable = $cond instanceof Group && $common_relationship === null;
		$is_suitable = $is_suitable || ($cond instanceof Group\Relationship && $common_relationship === $cond->relationship);
		
		if (!$is_suitable) {
			if ($common_relationship === null) {
				$group = $this->orm->condition_group();
			}else {
				$group = $this->orm->relationship_group($common_relationship);
				$this->merger->shift_relationship($cond, $common_relationship);
			}
			
			$group->logic = $cond->logic;
			$group->add($cond,  'and');
			$left[$target] = $cond = $group;
		}elseif($cond->negated()) {
			$group = $this->orm->condition_group();
			$group->logic = 'and';
			$group->set_conditions($cond->conditions());
			$group->negate();
			$cond->negate();
			$cond->set_conditions(array($group));
		}
		
		
		
		if (!($right instanceof Group) || !$this->merger->merge_groups($cond, $right, $this->logic_precedance)) {
			if($right instanceof Group\Relationship)
				$right = $this->merger->shift_relationship($right, $common_relationship);
			$cond->add($right, $right->logic);
		}
		
		
		if ($optimize_merge) {
			$left[$target] = $cond = $this->optimize_group($cond);
		}
		
		if (count($left) === 1) {
			if (!$cond->negated() && !($cond instanceof Group\Relationship)) {
				return $cond->conditions();
			}
		}
		
		return $left;
		
	}
	
	public function optimize($group) {
		//print_r($group); die;
		reset($group);
		return $this->expand_group($group);
	}
	
}