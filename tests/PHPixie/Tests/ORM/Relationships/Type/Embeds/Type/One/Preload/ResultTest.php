<?php

namespace PHPixie\Tests\ORM\Relationships\Type\Embeds\Type\One\Preload;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Type\One\Preload\Result
 */
class ResultTest extends \PHPixie\Tests\ORM\Relationships\Type\Embeds\Preload\ResultTest
{
    protected $embeddedPath = 'tree.fairy';
    protected $iteratorData = array();
    
    public function setUp()
    {
        parent::setUp();
        
        foreach($this->data as $data) {
            $this->iteratorData[] = (object) array(
                                        'tree' => (object) array(
                                            'fairy' => $data
                                        )
                                    );
        }
        
        $this->iteratorData[] = null;
        $this->iteratorData[] = (object) array(
                                        'tree' => (object) array()
                                );
    }
    
    protected function result()
    {
        return new \PHPixie\ORM\Relationships\Type\Embeds\Type\One\Preload\Result($this->reusableResult, $this->embeddedPath);
    }
    
}