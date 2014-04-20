<?php

namespace PHPixie\ORM;

class Repositories
{
	protected $ormBuilder;
	protected $config;
	protected $repositories = array();
	
    public function __construct($ormBuilder, $config)
	{
		$this->ormBuilder = $ormBuilder;
		$this->config     = $config;
	}
	
	public function get($name)
	{
		if (!array_key_exists($name, $this->repositories)){
            $this->repositories[$name] = $this->buildRepository($name, $this->config->slice($modelName));
        
        return $this->repositories[$name];
	}
	
	protected function buildRepository($modelName, $modelConfig)
	{
		$connectionName = $modelConfig->get('connection', 'default');
		$driverName = $this->ormBuilder->databaseDriverName($connectionName);
		$driver = $this->ormBuilder->driver();
	    return $driver->repository($modelName, $modelConfig);
	}
}
