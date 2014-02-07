<?php

namespace PHPixie\ORM\Driver\Mongo\Repository\Embedded;

class Query extends \PHPixie\DB\Driver\Mongo\Query {
	
	protected $field_prefix;
	
	public function __construct($db, $connection, $parser, $config, $type, $field_prefix) {
		parent::__construct($db, $connection, $parser, $config, $type);
		$this->field_prefix = $field_prefix;
		$this->fields(array('$'));
	}
	
	public function fields($fields) {
		foreach($fields as &$field)
			$field = $this->field_prefix.'.'.$field;
			
		return parent::fields($fields);
	}
}