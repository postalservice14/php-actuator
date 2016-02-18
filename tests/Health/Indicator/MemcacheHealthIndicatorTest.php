<?php
namespace Actuator\Test\Health\Indicator;

use Actuator\Health\Indicator\MemcacheHealthIndicator;
use Actuator\Health\Status;

class MemcacheHealthIndicatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MemcacheHealthIndicator
     */
    private $healthIndicator;

    /**
     * @var \Memcache|\PHPUnit_Framework_MockObject_MockObject
     */
    private $memcache;

    public function testMemcachedIsUp()
    {
        $this->memcache
            ->expects($this->once())
            ->method('getversion')
            ->willReturn('1.4.4');
        $health = $this->healthIndicator->health();
        $this->assertEquals(Status::UP, $health->getStatus());
        $this->assertEquals('1.4.4', $health->getDetails()->version);
    }

    public function testMemcacheIsDown()
    {
        $this->memcache
            ->expects($this->once())
            ->method('getversion')
            ->willReturn(false);
        $health = $this->healthIndicator->health();
        $this->assertEquals(Status::DOWN, $health->getStatus());
        $this->assertEquals([], (array)$health->getDetails());
    }

    public function testMemcacheIsDownWithException()
    {
        $this->memcache
            ->expects($this->once())
            ->method('getversion')
            ->willThrowException(new \Exception('Memcache Failed'));
        $health = $this->healthIndicator->health();
        $this->assertEquals(Status::DOWN, $health->getStatus());

        $details = $health->getDetails();
        $this->assertEquals('Memcache Failed', $details->error);
    }

    protected function setUp()
    {
        $this->memcache = $this->getMock('\Memcache', ['getversion']);
        $this->healthIndicator = new MemcacheHealthIndicator($this->memcache);
    }
}
