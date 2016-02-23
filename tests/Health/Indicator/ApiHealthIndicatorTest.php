<?php
namespace Actuator\Test\Health\Indicator;

use Actuator\Health\Indicator\ApiHealthIndicator;
use Actuator\Health\Status;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;

class ApiHealthIndicatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ApiHealthIndicator
     */
    private $indicator;

    /**
     * @var Request|\PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    public function setUp()
    {
        $this->request = $this->getMockBuilder('\Guzzle\Http\Message\Request')
            ->disableOriginalConstructor()
            ->setMethods(['send', 'getStatusCode', 'isSuccessful'])
            ->getMock();
        $this->indicator = new ApiHealthIndicator($this->request);
    }

    /**
     * @test
     */
    public function healthSuccess()
    {
        $this->request
            ->expects($this->once())
            ->method('send')
            ->willReturn($this->getResponse(200));

        $health = $this->indicator
            ->health();

        $this->assertInstanceOf('\Actuator\Health\Health', $health);
        $this->assertEquals(Status::UP, $health->getStatus());

        $details = $health->getDetails();
        $this->assertCount(1, $details);
        $this->assertEquals(200, $details['statusCode']);
    }

    /**
     * @test
     */
    public function healthFailure()
    {
        $this->request
            ->expects($this->once())
            ->method('send')
            ->willReturn($this->getResponse(500));

        $health = $this->indicator
            ->health();

        $this->assertInstanceOf('\Actuator\Health\Health', $health);
        $this->assertEquals(Status::DOWN, $health->getStatus());

        $details = $health->getDetails();
        $this->assertCount(1, $details);
    }

    /**
     * @param $code
     * @return Response
     */
    private function getResponse($code)
    {
        return new Response($code);
    }
}
