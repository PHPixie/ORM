<?php

namespace PHPixie\ORM\Driver\Mongo\Mapper;

class Group
{
    public function requiresSubquery($model, $relationship)
    {
        $path = explode('.', $relationship);
        $current = $modelName;
        foreach ($path as $relation) {
            $config = $this->configs[$current][$relation];
            if ($config['method'] === 'reference')
                return true;
            $current = $config['model_name'];
        }

        return false;
    }

    public function prepareQueries($conditions, $runner)
    {
        foreach ($conditions as $key => $condition) {
            if (!is_numeric($key)) {
                $subquery = $this->buildSubquery($modelName, $key)
            }

        }
    }

    public function

}
