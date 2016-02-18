<?php
namespace Actuator\Test\Health\Indicator;

use Actuator\Health\Indicator\DoctrineConnectionHealthIndicator;
use Actuator\Health\Status;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;

class DoctrineConnectionHealthIndicatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DoctrineConnectionHealthIndicator
     */
    private $healthIndicator;

    /**
     * @var Connection|\PHPUnit_Framework_MockObject_MockObject
     */
    private $connection;

    protected function setUp()
    {
        $this->connection = $this->getMockBuilder('\Doctrine\DBAL\Connection')
            ->disableOriginalConstructor()
            ->setMethods(['query'])
            ->getMock();

        $this->healthIndicator = new DoctrineConnectionHealthIndicator($this->connection);
    }

    /**
     * @test
     */
    public function doctrineIsUp()
    {
        $this->connection
            ->expects($this->once())
            ->method('query');

        $health = $this->healthIndicator
            ->health();

        $this->assertEquals(Status::UP, $health->getStatus());
    }

    /**
     * @test
     */
    public function doctrineIsDown()
    {
        $this->connection
            ->expects($this->once())
            ->method('query')
            ->willThrowException(new DBALException('Doctrine Error'));

        $health = $this->healthIndicator
            ->health();

        $this->assertEquals(Status::DOWN, $health->getStatus());

        $details = $health->getDetails();
        $this->assertEquals('Doctrine Error', $details->error);
    }
}
