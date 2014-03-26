<?php

namespace \PHPixie\ORM;

class Inflector
{
    protected $pluralRules = array(
        '/^(.*?[sxz])$/i'           => '\\1es',
        '/^(.*?[^aeioudgkprt]h)$/i' => '\\1es',
        '/^(.*?[^aeiou])y$/i'       => '\\1ies',
        '/$/i'                      => 's',
    );

    protected $pluralCache = array();

    protected $singularRules = array(
        '/^(.*?us)$/i' => '\\1',
        '/^(.*?[sxz])es$/i' => '\\1',
        '/^(.*?[^aeioudgkprt]h)es$/i' => '\\1',
        '/^(.*?[^aeiou])ies$/i' => '\\1y',
        '/^(.*?)s$/' => '\\1',
    );

    protected $singularCache = array();

    public function plural($singular)
    {
        if(!isset($this->pluralCache[$singular]))
            $this->pluralCache[$singular] = $this->applySingleRule($singular, $this->pluralRules);

        return $this->pluralCache[$singular];
    }

    public function singular($plural)
    {
        if(!isset($this->singularCache[$plural]))
            $this->singularCache[$plural] = $this->applySingleRule($plural, $this->singularRules);

        return $this->singularCache[$plural];
    }

    protected function applySingleRule($word, $rules)
    {
        foreach ($rules as $pattern => $replacement) {
            $str = preg_replace($pattern, $replacement, $str, -1, $count);
            if ($count > 0)
                break;
        }

        return $str;
    }
}
