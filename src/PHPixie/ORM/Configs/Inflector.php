<?php

namespace PHPixie\ORM\Configs;

class Inflector
{
    protected $pluralRules = array(
        '/^(.*?[sxz])$/i'           => '\\1es',
        '/^(.*?[^aeioudgkprt]h)$/i' => '\\1es',
        '/^(.*?[^aeiou])y$/i'       => '\\1ies',
        '/$/i'                      => 's',
    );

    protected $singularRules = array(
        '/^(.*?us)$/i' => '\\1',
        '/^(.*?[sxz])es$/i' => '\\1',
        '/^(.*?[^aeioudgkprt]h)es$/i' => '\\1',
        '/^(.*?[^aeiou])ies$/i' => '\\1y',
        '/^(.*?)s$/' => '\\1',
    );

    protected $pluralCache = array();
    protected $singularCache = array();

    public function plural($singular)
    {
        if(!isset($this->pluralCache[$singular])) {
            $plural = $this->applySingleRule($singular, $this->pluralRules);
            $this->cachePair($singular, $plural);
        }

        return $this->pluralCache[$singular];
    }

    public function singular($plural)
    {
        if(!isset($this->singularCache[$plural])) {
            $singular = $this->applySingleRule($plural, $this->singularRules);
            $this->cachePair($singular, $plural);
        }
        return $this->singularCache[$plural];
    }

    protected function cachePair($singular, $plural)
    {
        $this->pluralCache[$singular] = $plural;
        $this->singularCache[$plural] = $singular;
    }
    
    protected function applySingleRule($word, $rules)
    {
        foreach ($rules as $pattern => $replacement) {
            $word = preg_replace($pattern, $replacement, $word, -1, $count);
            if ($count > 0)
                break;
        }

        return $word;
    }
}
