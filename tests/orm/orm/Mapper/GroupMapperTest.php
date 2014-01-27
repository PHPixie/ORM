<?php

class GroupMapperTest extends PHPUnit_Framework_TestCase {
	
	protected $orm;
	protected $mapper;
	
	public function setUp() {
		$this->orm = new \PHPixie\ORM(null);
		$this->mapper = new PHPixie\ORM\Mapper\Group($this->orm);
	}
	
	public function testMap() {
	/*
		$builder = $this->builder()
								->_and('b.f1', 1)
								->_and('b.f1', 1)
								->_or('a.f', 2)
								->_and('b.f', 2)
								
								->_or('a.f', 2)
								->_and('b.f', 2)
								->_and('c.f', 2)
								
								->_or('k.f2', 1)
								;
		$this->assertMap($builder);
		*/
	}
	
	protected function extract_conditions($conds) {
		$arr = array();
		
		foreach($conds as $cond) {
			$prefix = $cond->logic;
			if ($cond->relationship !== null) {
				$prefix.= $prefix?'_':'';
				$prefix .= $cond->relationship;
			}
			
			if ($cond instanceof PHPixie\ORM\Conditions\Condition\Group) {
				$arr[] = array(
					$prefix => $this->extract_conditions($cond->conditions()));
			}else {
				$arr[] = $prefix.'.'.$cond->field;
			}
		}
		
		return($arr);
	}
	
	protected function assertMap($builder) {
		$conds = $this->mapper->map($builder->get_conditions());
		print_r($this->extract_conditions($conds));
	}
	
	protected function builder() {
		return new \PHPixie\ORM\Conditions\Builder($this->orm);
	}
}