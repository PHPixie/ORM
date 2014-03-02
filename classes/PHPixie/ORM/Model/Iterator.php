<?php

namespace PHPixie\ORM\Model;

abstract class Iterator implements \Iterator {

	public function as_array($models_as_arrays = false) {
		$res = array();
		foreach($this as $model) {
			if ($models_as_arrays) {
				$res[] = $model->as_array();
			}else
				$res[] = $model;
		}
	}
}