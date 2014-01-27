<?php

namespace PHPixie\ORM\Driver\PDO;

class Repository {
	
	protected $id_field;
	
	public function save($model) {
		$id_field = $this->id_field;
		$data = $this->get_data($model);
		
		$has_id = isset($data[$id_field]);
		$query = $this->connection->query($has_id?'update':'insert')
													->table($this->table)
													->data($data);
		if ($has_id)
			$query->where($id_field, $data[$id_field]);
			
		$query->execute();
		
		if (!$has_id)
			$model->$id_field = $this->connection->insert_id();
	}
	
	public function delete($model) {
		$id_field = $this->id_field;
		if (!isset($model->$id_field))
			throw new \PHPixie\ORM\Exception("This model hasn't been saved to the database, so it can't be deleted");
			
		$this->connection->query('delete')
										->table($this->table)
										->where($id_field, $model->$id_field)
										->execute();
	}
	
	public function 
	
	
	
	
}