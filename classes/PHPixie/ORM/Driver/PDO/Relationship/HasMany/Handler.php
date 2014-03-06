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
            'remote_key' => $config->get('remote_key', $modelName.'_id'),
            'id_field' => $targetId
        );
    }

    public function set($relationshipName, $model, $item)
    {
        $config = $this->config($model, $relationshipName);

        if (!$model->loaded())
            throw new \PHPixie\ORM\Exception\Relationship("You should save the model before assigning a has_many relationship to it.");

        if (!$item->modelName() !== $config['model'])
            throw new \PHPixie\ORM\Exception\Relationship("You can only assign '{$config['model']}' models to this relationship.");

        $item->setProperty($config['remote_key'], $model->id());

        if ($item->loaded())
            $item->save();
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
