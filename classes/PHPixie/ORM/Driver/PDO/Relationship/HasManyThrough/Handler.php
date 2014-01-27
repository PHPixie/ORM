<?php

namespace \PHPixie\ORM\Driver\PDO\Relationship\HasMany;

class Handler extends \PHPixie\ORM\Relationship\Handler{
	
	protected abstract function normalize_config($model_name, $relationship_name) {
		$config = $registry->get($model_name)->relationship_config($relationship_name);
		$target_id = $registry->get($config['model'])->id_field();
		
		return array(
			'model' => $config->get('model', $relationship_name),
			'linker_table' => $config->get('linker_table', $relationship_name),1
			'linker_key' => $config->get('linker_table', $relationship_name),
			'linker_model_key' => $config->get('linker_table', $relationship_name),
			'id_field' => $target_id
		);
	}
	
	public function check_valid($model, $item, $config) {
		
		if (!$model->loaded())
			throw new \PHPixie\ORM\Exception\Relationship("You should save the model before adding a has_many_through relationship to it.");
			
		if (!$item->loaded())
			throw new \PHPixie\ORM\Exception\Relationship("You should save the model before adding it to a has_many_through relationship.");
			
		if ($item->model_name() !== $config['model'])
			throw new \PHPixie\ORM\Exception\Relationship("You can only assign '{$config['model']}' models to this relationship.");
	}
	
	public function add($relationship_name, $model, $item) {
		$config = $this->config($model, $relationship_name);
		$this->check_valid($model, $item, $config);
		
		$this->db->query('insert')
						->table($config['linker_table'])
						->data(array(
							$config['linker_key'] => $model->id,
							$config['linker_model_key'] => $item->id(),
						))
						->execute();
	}
	
	
	
	public function remove($relationship_name, $model, $item) {
		$config = $this->config($model, $relationship_name);
		$this->check_valid($model, $item, $config);
		
		$this->db->query('delete')
						->table($config['linker_table'])
						->where($config['linker_key'], $model->id())
						->where($config['linker_model_key'], $item->id())
						->execute();
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