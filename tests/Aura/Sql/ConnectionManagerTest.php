<?php
namespace Aura\Sql;
use Aura\Di\Forge;
use Aura\Di\Config;

/**
 * Test class for ConnectionManager.
 * Generated by PHPUnit on 2011-07-11 at 09:43:46.
 */
class ConnectionManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConnectionManager
     */
    protected $manager;
    
    protected $default = [
        'adapter'  => 'mock',
        'dsn'      => ['host' => 'default.example.com', 'dbname' => 'test'],
        'username' => 'default_user',
        'password' => 'default_pass',
        'options'  => [],
    ];
    
    protected $masters = [
        // uses defaults
        'master1' => [],
        // overrides the dsn host
        'master2' => [
            'dsn' => ['host' => 'master2.example.com'],
        ],
    ];
    
    protected $slaves = [
        // uses defaults
        'slave1' => [],
        // overrides the dsn host
        'slave2' => [
            'dsn' => ['host' => 'slave2.example.com'],
        ],
        // overrides the dsn host
        'slave3' => [
            'dsn' => ['host' => 'slave3.example.com'],
        ],
    ];
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
    }
    
    protected function newManager(
        array $default = [],
        array $masters = [],
        array $slaves  = []
    ) {
        $forge = new Forge(new Config);
        $map = [
            'mock' => 'Aura\Sql\Connection\MockConnection',
        ];
        $factory = new ConnectionFactory($forge, $map);
        return new ConnectionManager($factory, $default, $masters, $slaves);
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
     * @todo Implement testGetRead().
     */
    public function testGetReadDefaultOnly()
    {
        $manager = $this->newManager($this->default);
        $conn = $manager->getRead();
        $expect = 'default.example.com';
        $actual = $conn->getDsnHost();
        $this->assertSame($expect, $actual);
    }
    
    public function testGetReadDefaultAndMasters()
    {
        $manager = $this->newManager($this->default, $this->masters);
        $expect = [
            'default.example.com',
            'master2.example.com',
        ];
        
        // try 10 times to make sure we get lots of random responses
        for ($i = 1; $i <= 10; $i++) {
            $conn = $manager->getRead();
            $actual = $conn->getDsnHost();
            $this->assertTrue(in_array($actual, $expect));
        }
    }
    
    public function testGetReadDefaultMastersAndSlaves()
    {
        $manager = $this->newManager($this->default, $this->masters, $this->slaves);
        $expect = [
            'default.example.com',
            'slave2.example.com',
            'slave3.example.com',
        ];
        
        // try 10 times to make sure we get lots of random responses
        for ($i = 1; $i <= 10; $i++) {
            $conn = $manager->getRead();
            $actual = $conn->getDsnHost();
            $this->assertTrue(in_array($actual, $expect));
        }
    }
    
    public function testGetReadDefaultAndSlaves()
    {
        $manager = $this->newManager($this->default, [], $this->slaves);
        $expect = [
            'default.example.com',
            'slave2.example.com',
            'slave3.example.com',
        ];
        
        // try 10 times to make sure we get lots of random responses
        for ($i = 1; $i <= 10; $i++) {
            $conn = $manager->getRead();
            $actual = $conn->getDsnHost();
            $this->assertTrue(in_array($actual, $expect));
        }
    }
    
    /**
     * @todo Implement testGetWrite().
     */
    public function testGetWriteDefaultOnly()
    {
        $manager = $this->newManager($this->default);
        $conn = $manager->getWrite();
        $expect = 'default.example.com';
        $actual = $conn->getDsnHost();
        $this->assertSame($expect, $actual);
    }
    
    public function testGetWriteDefaultAndMasters()
    {
        $manager = $this->newManager($this->default, $this->masters);
        $expect = [
            'default.example.com',
            'master2.example.com',
        ];
        
        // try 10 times to make sure we get lots of random responses
        for ($i = 1; $i <= 10; $i++) {
            $conn = $manager->getWrite();
            $actual = $conn->getDsnHost();
            $this->assertTrue(in_array($actual, $expect));
        }
    }
    
    public function testGetWriteDefaultMastersAndSlaves()
    {
        $manager = $this->newManager($this->default, $this->masters, $this->slaves);
        $expect = [
            'default.example.com',
            'master2.example.com',
        ];
        
        // try 10 times to make sure we get lots of random responses
        for ($i = 1; $i <= 10; $i++) {
            $conn = $manager->getWrite();
            $actual = $conn->getDsnHost();
            $this->assertTrue(in_array($actual, $expect));
        }
    }
    
    public function testGetWriteDefaultAndSlaves()
    {
        $manager = $this->newManager($this->default, [], $this->slaves);
        $expect = [
            'default.example.com',
        ];
        
        // try 10 times to make sure we get lots of random responses
        for ($i = 1; $i <= 10; $i++) {
            $conn = $manager->getWrite();
            $actual = $conn->getDsnHost();
            $this->assertTrue(in_array($actual, $expect));
        }
    }
    
    /**
     * @todo Implement testGetDefault().
     */
    public function testGetDefault()
    {
        $manager = $this->newManager($this->default);
        $conn = $manager->getDefault();
        $this->assertInstanceOf('Aura\Sql\Connection\MockConnection', $conn);
        
        $expect = $this->default;
        unset($expect['adapter']);
        $this->assertSame($conn->getParams(), $expect);
    }

    /**
     * @todo Implement testGetMaster().
     */
    // get a master by key; randomness was ascertained by getRead/getWrite
    public function testGetMaster()
    {
        $manager = $this->newManager($this->default, $this->masters);
        
        $conn = $manager->getMaster('master1');
        $expect = 'default.example.com';
        $actual = $conn->getDsnHost();
        $this->assertSame($expect, $actual);
        
        $conn = $manager->getMaster('master2');
        $expect = 'master2.example.com';
        $actual = $conn->getDsnHost();
        $this->assertSame($expect, $actual);
    }
    
    /**
     * @expectedException Aura\Sql\Exception\NoSuchMaster
     */
    public function testNoSuchMaster()
    {
        $manager = $this->newManager($this->default);
        $actual = $manager->getMaster('master1');
    }
    
    /**
     * @todo Implement testGetSlave().
     */
    public function testGetSlave()
    {
        $manager = $this->newManager($this->default, $this->masters, $this->slaves);
        
        $conn = $manager->getSlave('slave1');
        $expect = 'default.example.com';
        $actual = $conn->getDsnHost();
        $this->assertSame($expect, $actual);
        
        $conn = $manager->getSlave('slave2');
        $expect = 'slave2.example.com';
        $actual = $conn->getDsnHost();
        $this->assertSame($expect, $actual);
        
        $conn = $manager->getSlave('slave3');
        $expect = 'slave3.example.com';
        $actual = $conn->getDsnHost();
        $this->assertSame($expect, $actual);
    }
    
    /**
     * @expectedException Aura\Sql\Exception\NoSuchSlave
     */
    public function testNoSuchSlave()
    {
        $manager = $this->newManager($this->default);
        $actual = $manager->getSlave('slave1');
    }
    
}
