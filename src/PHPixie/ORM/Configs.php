<?php

namespace PHPixie\ORM;

/**
 * Class Configs
 * @package PHPixie\ORM
 */
class Configs
{
    /**
     * @var Configs\Inflector
     */
    protected $inflector;

    /**
     * @return Configs\Inflector
     */
    public function inflector()
    {
        if ($this->inflector === null) {
            $this->inflector = $this->buildInflector();
        }

        return $this->inflector;
    }

    /**
     * @return Configs\Inflector
     */
    protected function buildInflector()
    {
        return new \PHPixie\ORM\Configs\Inflector();
    }
}