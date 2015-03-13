<?php

namespace PHPixie\Tests\ORM\Configs;

/**
 * @coversDefaultClass \PHPixie\ORM\Configs\Config
 */
abstract class ConfigTest extends \PHPixie\Test\Testcase
{
    protected $config;
    protected $inflector;
    protected $plural = array();
    protected $sets = array();

    public function setUp()
    {
        $this->inflector = $this->quickMock('\PHPixie\ORM\Configs\Inflector');

        $plural = $this->plural;
        $singular = array_flip($this->plural);

        $this->inflector
            ->expects($this->any())
            ->method('plural')
            ->will($this->returnCallback(function($key) use($plural){
                return $plural[$key];
            }));

        $this->inflector
            ->expects($this->any())
            ->method('singular')
            ->will($this->returnCallback(function($key) use($singular){
                return $singular[$key];
            }));

        $this->config = $this->getConfig($this->sets[0][0]);
    }

    /**
     * @covers ::__construct
     * @covers \PHPixie\ORM\Configs\Config::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        foreach($this->sets as $set) {
            $config = $this->getConfig($set[0]);
            foreach($set[1] as $key => $value) {
                $this->assertEquals($value, $config->$key);
            }
        }
    }

    /**
     * @covers ::get
     * @covers \PHPixie\ORM\Configs\Config::get
     * @covers ::<protected>
     */
    public function testGet()
    {
        $data = $this->sets[0][1];
        $key = key($data);
        $value = current($data);
        $this->assertEquals($value, $this->config->get($key));
    }

    protected function slice($data, $requiredData = array())
    {
        $slice = $this->abstractMock('\PHPixie\Slice\Data', array('get', 'getRequired'));
        $slice
            ->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function($key) use($data){

                if(array_key_exists($key, $data))
                    return $data[$key];

                $args = func_get_args();
                if(count($args) == 2)
                    return $args[1];

                throw new \Exception("Key $key is not set.");
            }));
        
        $slice
            ->expects($this->any())
            ->method('getRequired')
            ->will($this->returnCallback(function($key) use($requiredData){

                if(array_key_exists($key, $requiredData))
                    return $requiredData[$key];

                throw new \Exception("Key $key is not set.");
            }));
        return $slice;
    }

    abstract protected function getConfig($slice);
}
