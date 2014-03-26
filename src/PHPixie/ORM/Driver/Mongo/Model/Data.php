<?php

namespace PHPixie\ORM\Driver\Mongo\Model;

class Data {
	protected $subdocument;
	protected $originalData;
	public function __construct($subdocument, $originalData) {
		$this->subdocument = $subdocument;
		$this->originalData = $originalData;
	}
	
	public function setModel($model)
	{
		$this->subdocument->setTarget($model);
	}
}