<?php

namespace PHPixie\ORM;

class Models
{
    /**
     * @type \PHPixie\ORM\Builder
     */
    protected $ormBuilder;
    protected $configSlice;
    /**
     * @var array|null
     */
    protected $wrappers;

    /**
     * @type \PHPixie\ORM\Models\Type\Database
     */
    protected $databaseModel;

    /**
     * @type \PHPixie\ORM\Models\Type\Embedded
     */
    protected $embeddedModel;

    /**
     * Models constructor.
     * @param $ormBuilder \PHPixie\ORM\Builder
     * @param $configSlice
     * @param null|array $wrappers
     */
    public function __construct($ormBuilder, $configSlice, $wrappers = null)
    {
        $this->ormBuilder   = $ormBuilder;
        $this->configSlice  = $configSlice;
        $this->wrappers     = $wrappers;
    }

    /**
     * @param $modelName string
     * @return mixed
     */
    public function modelConfigSlice($modelName)
    {
        return $this->configSlice->slice($modelName);
    }

    /**
     * @return null|array
     */
    public function wrappers()
    {
        return $this->wrappers;
    }

    /**
     * @return Models\Type\Database
     */
    public function database()
    {
        if($this->databaseModel === null)
        {
            $this->databaseModel = $this->buildDatabaseModel();
        }
        
        return $this->databaseModel;
    }

    /**
     * @return Models\Type\Embedded
     */
    public function embedded()
    {
        if($this->embeddedModel === null)
        {
            $this->embeddedModel = $this->buildEmbeddedModel();
        }
        
        return $this->embeddedModel;
    }

    /**
     * @return Models\Type\Database
     */
    protected function buildDatabaseModel()
    {
        return new \PHPixie\ORM\Models\Type\Database(
            $this,
            $this->ormBuilder->configs(),
            $this->ormBuilder->database(),
            $this->ormBuilder->drivers()
        );
    }

    /**
     * @return Models\Type\Embedded
     */
    protected function buildEmbeddedModel()
    {
        return new \PHPixie\ORM\Models\Type\Embedded(
            $this,
            $this->ormBuilder->configs(),
            $this->ormBuilder->data(),
            $this->ormBuilder->maps()
        );
    }
}