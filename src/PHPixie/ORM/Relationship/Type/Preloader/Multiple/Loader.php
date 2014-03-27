<?php

namespace PHPixie\ORM\Model\Preloader\Multiple;

class Loader extends \PHPixie\ORM\Model\Iterator implements \Countable
{
    protected $preloader;
    protected $ids;
    protected $count;

    public function __construct($preloader, $ids)
    {
        $this->preloader = $preloader;
        $this->ids = $ids;
        $this->count = count($ids);
    }
    
    public function getIterator()s
    {
        
    }
    
    public function count()
    {
        return $this->count;
    }
}
