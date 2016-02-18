<?php
namespace Actuator\Test\Health\Indicator;

use Actuator\Health\Indicator\MemcachedHealthIndicator;
use Actuator\Health\Status;

class MemcachedHealthIndicatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MemcachedHealthIndicator
     */
    private $healthIndicator;

    /**
     * @var \Memcached|\PHPUnit_Framework_MockObject_MockObject
     */
    private $memcached;

    /**
     * @test
     */
    public function memcachedIsUp()
    {
        $this->memcached
            ->expects($this->once())
            ->method('getversion')
            ->willReturn('1.4.4');
        $health = $this->healthIndicator->health();
        $this->assertEquals(Status::UP, $health->getStatus());
        $this->assertEquals('1.4.4', $health->getDetails()->version);
    }

    /**
     * @test
     */
    public function memcacheIsDown()
    {
        $this->memcached
            ->expects($this->once())
            ->method('getversion')
            ->willReturn(false);
        $health = $this->healthIndicator->health();
        $this->assertEquals(Status::DOWN, $health->getStatus());
        $this->assertEquals([], (array)$health->getDetails());
    }

    /**
     * @test
     */
    public function memcacheIsDownWithException()
    {
        $this->memcached
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
        $this->memcached = $this->getMock('\Memcached', ['getversion']);
        $this->healthIndicator = new MemcachedHealthIndicator($this->memcached);
    }


}
