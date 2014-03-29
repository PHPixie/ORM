<?php

namespace PHPixe\ORM\Relationships\Embeds;

class Handler extends \PHPixie\ORM\Relationship\Type\Handler
{
    public function mapRelationship($side, $group, $query, $plan)
    {
        $config = $side->config();
        $this->mapper->mapConditionGroup($group->conditions, $query, $config->embeddedMap());
    }
    
	public function getEmbedded($model, $embedConfig)
	{
		$path = getPath($model, $embedConfig);
		$subdocument = $this->getSubdocument($model->data(), explode('.', $path));
		
		if ($subdocument === null)
			return null;
		
		return $this->embeddedModel($embedConfig, $subdocument, $path);
	}

	public function createEmbedded($model, $embedConfig)
	{
		$path = getPath($model, $embedConfig);
		$subdocument = $this->createSubdocument($model->data(), explode('.', $path));
		return $this->embeddedModel($embedConfig, $subdocument, $path);
	}
	
	public function setEmbedded($model, $embedConfig, $embeddedModel)
	{
		$path = getPath($model, $embedConfig);
		$this->checkEmbeddedClass($embedConfig, $embeddedModel);
		$this->setSubdocument($model->data(), explode('.', $path), $embeddedModel->data());
	}
	
	protected function removeEmbedded($model, $embedConfig)
	{
		$path = getPath($model, $embedConfig);
		$subdocument = $this->removeSubdocument($model->data(), explode('.', $path));
	}
	
	protected function checkEmbeddedClass($embedConfig, $embeddedModel)
	{
		if (!($embeddedModel instanceof $embedConfig->modelClass))
			throw new \PHPixie\ORM\Exception\Handler("Only isntances of '{$embedConfig->modelClass}' can be used for this relationship.");
	}
	
	protected function getPath($model, $embedConfig)
	{
		$path = $embedConfig->path;
		if ($model instanceof Model)
			$path = $model->path().'.'.$path;
			
		return $path;
	}
	
	protected function embeddedModel($embedConfig, $subdocument, $path)
	{
		return $this->relationship->embeddedModel($embedConfig, $subdocument, $path)
	}
	
	protected function getSubdocument($document, $path, $createMissing = false)
	{
		$documentPlanner = $this->planners->document();
		$current = $document;
		
		foreach($path as $step) {
			$next = $documentPlanner->get($current, $step);
			if ($next === null) {
				if (!$createMissing)
					return null;
				$next = $documentPlanner->add($current, $step);
			}
			
			$current = $next;
		}
		
		return $current;	
	}
	
	protected function setSubdocument($document, $path, $subdocument)
	{
		$last = array_pop($path);
		$parent = $this->getSubdocument($document, $path, true);
		return $this->planners->document()->set($parent, $last, $subdocument);
	}
	
	protected function removeSubdocument($document, $path)
	{
		$documentPlanner = $this->planners->document();
		
		$last = array_pop($path);
		$parent = $this->getSubdocument($document, $path);
		
		if ($parent !== null && $documentPlanner->exists($parent, $last))
			$documentPlanner->remove($parent, $last);
	}
	
	protected function createSubdocument($document, $path)
	{
		$last = array_pop($path);
		$parent = $this->getSubdocument($document, $path, true);
		return $this->planners->document()->create($parent, $last);
	}
	
}
