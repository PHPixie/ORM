<?php

namespace \PHPixie\ORM\Driver\PDO\Relationship\HasOne;

class Handler extends \PHPixie\ORM\Driver\PDO\Relationship\HasMany\Handler {
	
	public function get() {
		$this->query($property_name, $model)->find();
	}
}