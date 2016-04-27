<?php

namespace Actuator\Test\Health\Indicator;

use Actuator\Health\Indicator\GuzzleRequestHealthIndicator;
use Actuator\Health\Status;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;

class GuzzleRequestHealthIndicatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GuzzleRequestHealthIndicator
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
        $this->indicator = new GuzzleRequestHealthIndicator($this->request);
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
     * @param int  $code Actual Response Code
     * @param null $body
     *
     * @return Response
     */
    private function getResponse($code, $body = null)
    {
        return new Response($code, null, $body);
    }

    /**
     * @test
     */
    public function healthFailure()
    {
        $this->request
            ->expects($this->once())
            ->method('send')
            ->willReturn($this->getResponse(500, 'Response Body'));

        $health = $this->indicator
            ->health();

        $this->assertInstanceOf('\Actuator\Health\Health', $health);
        $this->assertEquals(Status::DOWN, $health->getStatus());

        $details = $health->getDetails();
        $this->assertCount(2, $details);
        $this->assertEquals(500, $details['statusCode']);
        $this->assertEquals('Response Body', $details['body']);
    }
}
