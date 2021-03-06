<?php
namespace Aura\Sql;

use Aura\Di\Forge;
use Aura\Di\Config;

/**
 * Test class for ConnectionFactory.
 * Generated by PHPUnit on 2011-07-11 at 09:43:40.
 */
class ConnectionFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConnectionFactory
     */
    protected $factory;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        
        $forge = new Forge(new Config);
        
        $map = [
            'mock' => 'Aura\Sql\Connection\MockConnection',
        ];
        
        $this->factory = new ConnectionFactory($forge, $map);
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @todo Implement testNewInstance().
     */
    public function testNewInstance()
    {
        $params = [
            'dsn' => [],
            'username' => null,
            'password' => null,
            'options' => [],
        ];
        $conn = $this->factory->newInstance('mock', $params);
        $this->assertInstanceOf('Aura\Sql\Connection\MockConnection', $conn);
    }
    
    /**
     * @expectedException Aura\Sql\Exception\ConnectionFactory
     */
    public function testNoMapping()
    {
        $params = [
            'dsn' => [],
            'username' => null,
            'password' => null,
            'options' => [],
        ];
        $conn = $this->factory->newInstance('no_such_mapping', $params);
    }
}
