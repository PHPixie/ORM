<?php

namespace \PHPixie\ORM\Driver\PDO\Relationship\BalongsTo;

class Handler extends \PHPixie\ORM\Relationship\Handler{
	
	protected abstract function normalize_config($model_name, $relationship_name) {
		$config = $registry->get($model_name)->relationship_config($relationship_name);
		$target_id = $registry->get($config['model'])->id_field();
		
		return array(
			'model' => $config->get('model', $relationship_name),
			'key' => $config->get('model_name', $relationship_name.'_id'),
			'id_field' => $target_id
		);
	}
	
	public function set($relationship_name, $model, $owner) {
		$config = $this->config($model, $relationship_name);
		
		if (!$owner->loaded())
			throw new \PHPixie\ORM\Exception\Relationship("You should save the model before assigning it to a belongs_to relationship.");
			
		if (!$owner->model_name() !== $config['model'])
			throw new \PHPixie\ORM\Exception\Relationship("You can only assign '{$config['model_name']}' models to this relationship.");
		
		$model->set_property($config['key'], $owner->id());
		
		if ($model->loaded())
			$model->save();
	}
	
	public function get($property_name, $model) {
		return $this->orm->query($config['model'])
							->where($config['id_field'], $model->get_property($config['key']))
							->find();
	}
}