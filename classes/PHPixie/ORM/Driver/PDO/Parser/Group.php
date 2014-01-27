<?php

class Group {
	
	protected $logic_precedance = array(
		'and' => 2,
		'xor' => 1,
		'or'  => 0
	);
	
	public function __construct($driver, $operator_parser) {
		$this->driver = $driver;
		$this->operator_parser = $operator_parser;
	}
	
	protected function expand_group( & $group, $level = 0) {
		$res = array();
		
		$current = current($group);
		
		$relation = $current->relationship;
		
		if ($relation !== null) {
			if (!isset($res[$relation])) {
				$res[$relation] = array();
			}
			$res[$relation][]= $current;
		}else {
			$res[]= $current;
		}
		
		while (true) {
			if (($next = next($group)) === false)
				break;
				
			if ($this->logic_precedance[$next->logic] < $level) {
				prev($group);
				break;
			}
			
			$right = $this->expand_group($group, $this->logic_precedance[$next->logic] + 1, $all_subqueries, $subqueries);
			$res = $this->merge($res, $right);
				
			$current = $next;
		}
		
		return $res;
	}
	
	protected function merge($left, $right) {
		if ($right instanceof \PHPixie\ORM\Conditions\Condition\Group) {
			$key = $right->relationship;
			if ($key === null)
				$key = 0;
			$right = array($key => $this->expand_group($right->conditions()));
		}
		
		if (count($right) !== 1) {
			foreach($right as $condition)
				$left[] = $condition;
		}else {
			$key = key($right);
			$right = current($right);
			if ($key === 0) {
				$left[] = $right;
			}elseif (!isset($left[$key])) {
				$left[$key] = $right;
			}else {
				foreach($right as $condition)
					$left[$key][] = $condition;
			}
			
		}
		
		return $left;
	}
	
	public function map($group, $loader) {
		$this->expand_group($group);
	}
	
	
}