<?php

namespace PHPixie\ORM\Driver;

class PDO extends \PHPixie\PDO\Driver{

	public function repository($model_name, $model_config) {
		return new PDO\Repository($this->orm, $model_name, $model_config);
	}
	
}