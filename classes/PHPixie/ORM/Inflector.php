<?php

namespace \PHPixie\ORM;

class Inflector {
	
	protected $plural_rules = array(
		'/^(.*?[sxz])$/i'           => '\\1es',
		'/^(.*?[^aeioudgkprt]h)$/i' => '\\1es',
		'/^(.*?[^aeiou])y$/i'       => '\\1ies',
		'/$/i'                      => 's',
	);
	
	protected $plural_cache = array();
	
	protected $singular_rules = array(
		'/^(.*?us)$/i' => '\\1',
		'/^(.*?[sxz])es$/i' => '\\1',
		'/^(.*?[^aeioudgkprt]h)es$/i' => '\\1',
		'/^(.*?[^aeiou])ies$/i' => '\\1y',
		'/^(.*?)s$/' => '\\1',
	);
	
	protected $singular_cache = array();
	
	public function plural($singular) {
		if(!isset($this->plural_cache[$singular]))
			$this->plural_cache[$singular] = $this->apply_single_rule($singular, $this->plural_rules);
		return $this->plural_cache[$singular];
	}
	
	public function singular($plural) {
		if(!isset($this->singular_cache[$plural]))
			$this->singular_cache[$plural] = $this->apply_single_rule($plural, $this->singular_rules);
		return $this->singular_cache[$plural];
	}
	
	protected function apply_single_rule($word, $rules) {
		foreach ($rules as $pattern => $replacement) {
			$str = preg_replace($pattern, $replacement, $str, -1, $count);
			if ($count > 0)
				break;
		}
		return $str;
	}
}