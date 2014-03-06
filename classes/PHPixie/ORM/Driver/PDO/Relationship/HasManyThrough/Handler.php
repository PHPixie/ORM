<?php

namespace \PHPixie\ORM\Driver\PDO\Relationship\HasMany;

class Handler extends \PHPixie\ORM\Relationship\Handler
{
    abstract protected function normalizeConfig($modelName, $relationshipName)
    {
        $config = $registry->get($modelName)->relationshipConfig($relationshipName);
        $targetId = $registry->get($config['model'])->idField();

        return array(
            'model' => $config->get('model', $relationshipName),
            'linker_table' => $config->get('linker_table', $relationshipName),1
            'linker_key' => $config->get('linker_table', $relationshipName),
            'linker_model_key' => $config->get('linker_table', $relationshipName),
            'id_field' => $targetId
        );
    }

    public function checkValid($model, $item, $config)
    {
        if (!$model->loaded())
            throw new \PHPixie\ORM\Exception\Relationship("You should save the model before adding a has_many_through relationship to it.");

        if (!$item->loaded())
            throw new \PHPixie\ORM\Exception\Relationship("You should save the model before adding it to a has_many_through relationship.");

        if ($item->modelName() !== $config['model'])
            throw new \PHPixie\ORM\Exception\Relationship("You can only assign '{$config['model']}' models to this relationship.");
    }

    public function add($relationshipName, $model, $item)
    {
        $config = $this->config($model, $relationshipName);
        $this->checkValid($model, $item, $config);

        $this->db->query('insert')
                        ->table($config['linker_table'])
                        ->data(array(
                            $config['linker_key'] => $model->id,
                            $config['linker_model_key'] => $item->id(),
                        ))
                        ->execute();
    }

    public function remove($relationshipName, $model, $item)
    {
        $config = $this->config($model, $relationshipName);
        $this->checkValid($model, $item, $config);

        $this->db->query('delete')
                        ->table($config['linker_table'])
                        ->where($config['linker_key'], $model->id())
                        ->where($config['linker_model_key'], $item->id())
                        ->execute();
    }

    public function get($propertyName, $model)
    {
        $this->query($propertyName, $model)->findAll();
    }

    public function query($propertyName, $model)
    {
        $config = $this->config($model, $relationshipName);

        return $this->orm->query($config['model'])
                            ->where($config['id_field'], $model->getProperty($config['key']))
    }
}
