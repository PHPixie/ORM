<?php

namespace PHPixie\ORM;

class  Repository {
	
	
	public function get_data($model) {
		return get_object_vars($model);
	}
	
	public abstract function save(model);

}