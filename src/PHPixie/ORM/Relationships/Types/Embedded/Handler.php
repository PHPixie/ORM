<?php

namespace PHPixie\ORM\Relationships\Embeds;

abstract class Handler extends \PHPixie\ORM\Relationship\Type\Handler
{
    protected function explodePath($path)
    {
        return explode('.', $path);
    }
	
	protected function getDocument($document, $exploadedPath, $createMissing = false)
    {
        $documentPlanner = $this->planners->document();
        $current = $document;
        
        foreach($exploadedPath as $step) {
            $next = $documentPlanner->getDocument($current, $step);
            if ($next === null) {
                if (!$createMissing)
                    return null;
                $next = $documentPlanner->addDocument($current, $step);
            }
            
            $current = $next;
        }
        
        return $current;
    }
	
	protected function getDocumentAndKey($model, $path, $createMissing = false)
    {
        $path = $this->explodePath($path);
        $key = array_pop($path);
        $parent = $this->getDocument($model->data()->document(), $path, $createMissing);
        return array($parent, $key);
    }
    
}