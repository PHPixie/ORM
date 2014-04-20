<?php

namespace PHPixie\ORM\Planners

class Collection
{
    protected $models;
    protected $queries;
    protected $requiredModel;

    public function __construct($requiredModel, $items = array())
    {
        $this->requiredModel = $requiredModel;
		$this->add($items);
    }

    public function add($items)
    {
        if (!is_array($items)) {
            $items = array($items);
        }

        foreach($items as $item)
            $this->addItem($item);
    }

    protected function addItem($item, $recurseArray =  true)
    {
        if ($item instanceof \PHPixie\ORM\Model) {
            if ($item->modelName() !== $this->requiredModel)
                throw new \PHPixie\ORM\Exception\Mapper("Instance of the '{$item->modelName()}' model passed, but '{$this->requiredModel}' was expected.");

            if ($item->isNew())
                throw new \PHPixie\ORM\Exception\Mapper("You can only use saved models.");
			
			if (!$item->isDeleted())
                throw new \PHPixie\ORM\Exception\Mapper("You cannot use deleted models for relationships.");
				
            $this->models[] = $item;
        } elseif ($item instanceof \PHPixie\ORM\Query) {

            if ($item->modelName() !== $this->requiredModel)
                throw new \PHPixie\ORM\Exception\Mapper("Query for the '{$item->modelName()}' model passed, but '{$this->requiredModel}' was expected.");

            $this->queries[] = $item;
        }else
            throw new \PHPixie\ORM\Exception\Mapper("Only '{$this->requiredModel}' models and queries are allowed.");
    }

    public function addedModels()
    {
        return $this->models;
    }

    public function addedQueries()
    {
        return $this->queries;
    }

    public function modelFields($fields)
    {
        $data = array();
        foreach ($this->models as $model) {
            $dataRow = array();
            foreach($fields as $field)
                $dataRow[$field] = $model->$field;
            $data[] = $dataRow;
        }
        return $data;
    }

    public function modelField($field)
    {
        return array_column($this->modelFields(array($field), $field));
    }

}
