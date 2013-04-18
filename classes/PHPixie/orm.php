<?php

namespace PHPixie;

class ORM {
	
	/**
	 * Pixie Dependancy Container
	 * @var \PHPixie\Pixie
	 */
	public $pixie;
	
	/**
	 * Cache of ORM tables columns
	 * @var array
	 */
	public $column_cache = array();
	
	/**
	 * Initializes the ORM module
	 * 
	 * @param \PHPixie\Pixie $pixie Pixie dependency container
	 */
	public function __construct($pixie) {
		$this->pixie = $pixie;
	}
	
	/**
	 * Initializes ORM model by name, and optionally fetches an item by id
	 *
	 * @param string  $name Model name
	 * @param mixed $id   If set ORM will try to load the item with this id from the database
	 * @return \PHPixie\ORM\Model   ORM model, either empty or preloaded
	 */
	public function get($name, $id = null)
	{
		$model = $this->pixie->app_namespace."Model\\".$name;
		$model = new $model($this->pixie);
		if ($id != null)
		{
			$model = $model->where($model->id_field, $id)->find();
			$model->values(array($model->id_field => $id));
		}
		return $model;
	}
	
	/**
	 * Initialized an ORM Result with which model to use and which result to
	 * iterate over
	 *
	 * @param string          $model  Model name
	 * @param \PHPixie\DB\Result $dbresult Database result
	 * @param array           $with Array of rules for preloaded relationships
	 */
	public function result($model, $dbresult, $with = array()) {
		return new \PHPixie\ORM\Result($this->pixie, $model, $dbresult, $with);
	}
	
}