<?php

namespace PHPixie\ORM;

class Models
{
    /**
     * @type \PHPixie\ORM\Builder
     */
    protected $ormBuilder;
    protected $configSlice;
    protected $wrappers;

    /**
     * @type \PHPixie\ORM\Models\Type\Database
     */
    protected $databaseModel;

    /**
     * @type \PHPixie\ORM\Models\Type\Embedded
     */
    protected $embeddedModel;
    
    public function __construct($ormBuilder, $configSlice, $wrappers = null)
    {
        $this->ormBuilder   = $ormBuilder;
        $this->configSlice  = $configSlice;
        $this->wrappers     = $wrappers;
    }
    
    public function modelConfigSlice($modelName)
    {
        return $this->configSlice->slice($modelName);
    }
    
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
    
    protected function buildDatabaseModel()
    {
        return new \PHPixie\ORM\Models\Type\Database(
            $this,
            $this->ormBuilder->configs(),
            $this->ormBuilder->database(),
            $this->ormBuilder->drivers()
        );
    }
    
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