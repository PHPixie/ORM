<?php

namespace \PHPixie\ORM\Driver\PDO\Relationship\HasMany;

class Handler extends \PHPixie\ORM\Relationship\Handler{
	
	protected abstract function normalize_config($model_name, $relationship_name) {
		$config = $registry->get($model_name)->relationship_config($relationship_name);
		$target_id = $registry->get($config['model'])->id_field();
		
		return array(
			'model' => $config->get('model', $relationship_name),
			'remote_key' => $config->get('remote_key', $model_name.'_id'),
			'id_field' => $target_id
		);
	}
	
	public function set($relationship_name, $model, $item) {
		$config = $this->config($model, $relationship_name);
		
		if (!$model->loaded())
			throw new \PHPixie\ORM\Exception\Relationship("You should save the model before assigning a has_many relationship to it.");
			
		if (!$item->model_name() !== $config['model'])
			throw new \PHPixie\ORM\Exception\Relationship("You can only assign '{$config['model']}' models to this relationship.");
		
		$item->set_property($config['remote_key'], $model->id());
		
		if ($item->loaded())
			$item->save();
	}
	
	public function get($property_name, $model) {
		$this->query($property_name, $model)->find_all();
	}
	
	public function query($property_name, $model) {
		$config = $this->config($model, $relationship_name);
		
		return $this->orm->query($config['model'])
							->where($config['id_field'], $model->get_property($config['key']))
	}
}