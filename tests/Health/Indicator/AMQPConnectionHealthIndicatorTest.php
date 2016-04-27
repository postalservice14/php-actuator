<?php

namespace Actuator\Test\Health\Indicator;

use Actuator\Health\Indicator\AMQPConnectionHealthIndicator;
use Actuator\Health\Status;
use PhpAmqpLib\Connection\AMQPLazyConnection;

class AMQPConnectionHealthIndicatorTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function amqpIsUp()
    {
        $connection = $this->getMockBuilder('\\PhpAmqpLib\\Connection\\AbstractConnection')
            ->disableOriginalConstructor()
            ->setMethods(['reconnect', 'getServerProperties'])
            ->getMockForAbstractClass();

        $connection->expects($this->once())
            ->method('getServerProperties')
            ->willReturn(['version' => ['S', '3.3.0']]);

        $indicator = new AMQPConnectionHealthIndicator($connection);

        $health = $indicator->health();

        $this->assertEquals(Status::UP, $health->getStatus());
    }

    /** @test */
    public function amqpIsDown()
    {
        $connection = new AMQPLazyConnection('foobar.com', 5672, 'guest', 'guest', '/');
        $indicator = new AMQPConnectionHealthIndicator($connection);

        $health = $indicator->health();

        $this->assertEquals(Status::DOWN, $health->getStatus());
        $this->assertTrue(array_key_exists('error', $health->getDetails()));
    }
}
