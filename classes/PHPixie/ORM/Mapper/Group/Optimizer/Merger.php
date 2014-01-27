<?php

namespace PHPixie\ORM\Mapper\Group\Optimizer;
use \PHPixie\ORM\Conditions\Condition\Group;

class Merger {

	protected $orm;
	
	public function __construct($orm) {
		$this->orm = $orm;
	}
	
	public function common_relationship($left, $right) {
		if ($left === null || $right === null)
			return null;
			
		$l_len = strlen($left);
		$r_len = strlen($right);
		$min_len = min($l_len, $r_len);
		
		$common = null;
		$current = '';
		
		for ($i = 0; $i < $min_len; $i++) {
			$char = $left[$i];
			if ($char === $right[$i]) {
			
				if ($char === '.')
					$common = $current;
					
				$current.= $char;
			}else {
				return $common;
			}
		}
		
		if ($l_len === $r_len)
			return $current;
		
		$longer = $l_len > $r_len? $left:$right;
		if ($longer[$min_len] === '.')
			return $current;
			
		return $common;
	}
	
	public function find_merge_target($condition_list, $new_condition, $logic_precedance) {
		if (!($new_condition instanceof Group\Relationship))
			return null;
			
		$unrelated_subgroup = null;
		$next_available = true;
		
		for ($i = count($condition_list) - 1; $i >= 0; $i-- ) {
			$cond = $condition_list[$i];
			$new_prec = $logic_precedance[$new_condition->logic];
			$cond_prec = $i === 0 ? 0 : $logic_precedance[$cond->logic];
					
			$is_available = $next_available;
			$next_available = $cond_prec <= $new_prec;
					
			if ($cond_prec > $new_prec || !$is_available) 
				continue;
						
			if ($cond instanceof Group\Relationship) {
				$common = $this->common_relationship($cond->relationship, $new_condition->relationship);
					if ($common !== null)
						return $i;
				
			}elseif($unrelated_subgroup === null && $cond instanceof Group){
				$unrelated_subgroup = $i;
				continue;
			}
			
			
			if ($new_prec > $cond_prec)
				return null;
		}
		
		return $unrelated_subgroup;
	}
	
	public function merge_groups($left, $right, $logic_precedance) {
		if (($left instanceof Group\Relationship) xor ($right instanceof Group\Relationship))
			return false;
			
		if ($left instanceof Group\Relationship && $right instanceof Group\Relationship && $right->relationship != $left->relationship)
			return false;
		
		if ($left->negated() || $right->negated())
			return false;
		foreach($right->conditions() as $key=>$rcond)
			if ($key > 0 && $logic_precedance[$rcond->logic] < $logic_precedance[$right->logic])
				return false;
		foreach($right->conditions() as $key => $rcond)
			$left->add($rcond, $key === 0 ? $right->logic : $rcond->logic);
		return true;
	}
	
	public function shift_relationship($cond, $prefix) {
		if ($prefix === null)
			return $cond;
			
		$len = strlen($prefix);
		if ($cond->relationship === $prefix) {
			$group = $this->orm->condition_group();
			$group->set_conditions($cond->conditions());
			$group->logic = $cond->logic;
			if ($cond->negated())
				$group->negate();
			$cond = $group;
			
		}elseif(substr($cond->relationship, 0, $len) === $prefix) {
			$cond->relationship = substr($cond->relationship, $len + 1);
		}else
			throw new \PHPixie\ORM\Exception\Mapper("Relation {$prefix} doesn't start with $prefix");
		
		return $cond;
	}
	
}