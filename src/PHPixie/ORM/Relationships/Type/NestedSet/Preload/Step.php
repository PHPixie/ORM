<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet\Preloader;

class Children
{
    protected $result;
    protected $placeholder;
    
    public function __construct($result, $placeholder)
    {
        $this->result       = $result;
        $this->placeholder = $placeholder;
    }
    
    public function execute()
    {
        $leftKey  = $config->left;
        $rightKey = $config->right;
        
        $fields = $this->result->getFields(array($leftKey, $rightKey));
        foreach($fields as $itemData) {
            $this->placeholder
                ->startOrGroup()
                    ->where($leftKey, '>', $itemData[$leftKey])
                    ->where($rightKey, '<', $itemData[$rightKey])
                ->endGroup();
        }
    }
}
