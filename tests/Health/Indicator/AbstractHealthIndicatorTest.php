<?php

namespace Actuator\Test\Health\Indicator;

use Actuator\Health\Indicator\HealthIndicatorInterface;
use Actuator\Health\Status;

class AbstractHealthIndicatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HealthIndicatorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $indicator;

    public function setUp()
    {
        $this->indicator = $this->getMockForAbstractClass('\\Actuator\\Health\\Indicator\\AbstractHealthIndicator');
    }

    /** @test */
    public function exceptionThrownMeansStatusDown()
    {
        $this->indicator->expects($this->once())
            ->method('doHealthCheck')
            ->willThrowException(new \Exception('Foo'));

        $health = $this->indicator->health();

        $this->assertEquals(new Status(Status::DOWN), $health->getStatus());
        $this->assertEquals('Foo', $health->getDetails()['error']);
    }
}
