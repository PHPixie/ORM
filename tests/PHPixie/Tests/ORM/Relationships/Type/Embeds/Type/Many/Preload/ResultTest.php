<?php

namespace PHPixie\Tests\ORM\Relationships\Type\Embeds\Type\Many\Preload;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Preload\Result
 */
class ResultTest extends \PHPixie\Tests\ORM\Relationships\Type\Embeds\Preload\ResultTest
{
    protected $embeddedPath = 'tree.fairies';
    protected $iteratorData = array();
    
    public function setUp()
    {
        parent::setUp();
        
        for($i=0; $i<4; $i=$i+2) {
            $this->iteratorData[] = (object) array(
                                        'tree' => (object) array(
                                            'fairies' => array(
                                                $this->data[$i],
                                                $this->data[$i+1],
                                                null
                                            )
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
        return new \PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Preload\Result($this->reusableResult, $this->embeddedPath);
    }
    
}