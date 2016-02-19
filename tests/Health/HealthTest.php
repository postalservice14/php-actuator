<?php
namespace Actuator\Test\Health;

use Actuator\Health\HealthBuilder;
use Actuator\Health\Status;

class HealthTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function statusDefaultsToUnknown()
    {
        $builder = new HealthBuilder(null, null);
        $this->assertEquals(Status::UNKNOWN, $builder->build()->getStatus());
    }

    /**
     * @test
     */
    public function createWithStatus()
    {
        $builder = new HealthBuilder(new Status(Status::UP), array());
        $health = $builder->build();
        $this->assertEquals(Status::UP, $health->getStatus());
        $this->assertCount(0, $health->getDetails());
    }

    /**
     * @test
     */
    public function createWithDetails()
    {
        $builder = new HealthBuilder(new Status(Status::UP), array('a' => 'b'));
        $health = $builder->build();

        $this->assertEquals(Status::UP, $health->getStatus());
        $this->assertEquals(array('a' => 'b'), $health->getDetails());
    }

    /**
     * @test
     */
    public function equals()
    {
        $health1 = new HealthBuilder(new Status('UP'), array('a' => 'b'));
        $health2 = new HealthBuilder(new Status('UP'), array('a' => 'b'));

        $this->assertEquals($health1, $health2);
    }

    /**
     * @test
     */
    public function withException()
    {
        $exception = new \Exception('Test Exception');

        $healthBuilder = new HealthBuilder(new Status(Status::UP), array('a' => 'b'));
        $health = $healthBuilder->withException($exception)
            ->build();

        $this->assertEquals(array('a' => 'b', 'error' => 'Test Exception'), $health->getDetails());
    }

    /**
     * @test
     */
    public function withDetails()
    {
        $healthBuilder = new HealthBuilder(new Status(Status::UP), array('a' => 'b'));
        $health = $healthBuilder->withDetail('c', 'd')
            ->build();

        $this->assertEquals(array('a' => 'b', 'c' => 'd'), $health->getDetails());
    }

    /**
     * @test
     */
    public function withUnknownDetails()
    {
        $healthBuilder = new HealthBuilder();
        $health = $healthBuilder->unknown()
            ->withDetail('a', 'b')
            ->build();
        $this->assertEquals(Status::UNKNOWN, $health->getStatus());
        $this->assertEquals(array('a' => 'b'), $health->getDetails());
    }

    /**
     * @test
     */
    public function unknown()
    {
        $healthBuilder = new HealthBuilder();
        $health = $healthBuilder->unknown()
            ->build();

        $this->assertEquals(Status::UNKNOWN, $health->getStatus());
        $this->assertEquals(array(), $health->getDetails());
    }

    /**
     * @test
     */
    public function upWithDetails()
    {
        $healthBuilder = new HealthBuilder();
        $health = $healthBuilder->up()
            ->withDetail('a', 'b')
            ->build();

        $this->assertEquals(Status::UP, $health->getStatus());
        $this->assertEquals(array('a' => 'b'), $health->getDetails());
    }

    /**
     * @test
     */
    public function up()
    {
        $healthBuilder = new HealthBuilder();
        $health = $healthBuilder
            ->up()
            ->build();

        $this->assertEquals(Status::UP, $health->getStatus());
        $this->assertEquals(array(), $health->getDetails());
    }

    /**
     * @test
     */
    public function downWithException()
    {
        $healthBuilder = new HealthBuilder();
        $health = $healthBuilder->down(new \Exception('Exception Message'))
            ->build();

        $this->assertEquals(Status::DOWN, $health->getStatus());
        $this->assertEquals(array('error' => 'Exception Message'), $health->getDetails());
    }

    /**
     * @test
     */
    public function down()
    {
        $healthBuilder = new HealthBuilder();
        $health = $healthBuilder->down()
            ->build();

        $this->assertEquals(Status::DOWN, $health->getStatus());
        $this->assertEquals(array(), $health->getDetails());
    }

    /**
     * @test
     */
    public function outOfService()
    {
        $healthBuilder = new HealthBuilder();
        $health = $healthBuilder->outOfService()
            ->build();

        $this->assertEquals(Status::OUT_OF_SERVICE, $health->getStatus());
        $this->assertEquals(array(), $health->getDetails());
    }

    /**
     * @test
     */
    public function status()
    {
        $healthBuilder = new HealthBuilder();
        $health = $healthBuilder->status(Status::UP)
            ->build();

        $this->assertEquals(Status::UP, $health->getStatus());
        $this->assertEquals(array(), $health->getDetails());
    }
}
