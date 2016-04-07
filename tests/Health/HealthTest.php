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
        $builder = new HealthBuilder(new Status(Status::UP), []);
        $health = $builder->build();
        $this->assertEquals(Status::UP, $health->getStatus());
        $this->assertCount(0, (array) $health->getDetails());
    }

    /**
     * @test
     */
    public function createWithDetails()
    {
        $builder = new HealthBuilder(new Status(Status::UP), ['a' => 'b']);
        $health = $builder->build();

        $this->assertEquals(Status::UP, $health->getStatus());
        $this->assertEquals(['a' => 'b'], $health->getDetails());
    }

    /**
     * @test
     */
    public function equals()
    {
        $health1 = new HealthBuilder(new Status('UP'), ['a' => 'b']);
        $health2 = new HealthBuilder(new Status('UP'), ['a' => 'b']);

        $this->assertEquals($health1, $health2);
    }

    /**
     * @test
     */
    public function withException()
    {
        $exception = new \Exception('Test Exception');

        $health = (new HealthBuilder(new Status(Status::UP), ['a' => 'b']))->withException($exception)
            ->build();

        $this->assertEquals(['a' => 'b', 'error' => 'Test Exception'], $health->getDetails());
    }

    /**
     * @test
     */
    public function withDetails()
    {
        $health = (new HealthBuilder(new Status(Status::UP), ['a' => 'b']))->withDetail('c', 'd')
            ->build();

        $this->assertEquals(['a' => 'b', 'c' => 'd'], $health->getDetails());
    }

    /**
     * @test
     */
    public function withUnknownDetails()
    {
        $health = (new HealthBuilder())->unknown()
            ->withDetail('a', 'b')
            ->build();
        $this->assertEquals(Status::UNKNOWN, $health->getStatus());
        $this->assertEquals(['a' => 'b'], $health->getDetails());
    }

    /**
     * @test
     */
    public function unknown()
    {
        $health = (new HealthBuilder())->unknown()
            ->build();

        $this->assertEquals(Status::UNKNOWN, $health->getStatus());
        $this->assertEquals([], $health->getDetails());
    }

    /**
     * @test
     */
    public function upWithDetails()
    {
        $health = (new HealthBuilder())->up()
            ->withDetail('a', 'b')
            ->build();

        $this->assertEquals(Status::UP, $health->getStatus());
        $this->assertEquals(['a' => 'b'], $health->getDetails());
    }

    /**
     * @test
     */
    public function up()
    {
        $health = (new HealthBuilder())
            ->up()
            ->build();

        $this->assertEquals(Status::UP, $health->getStatus());
        $this->assertEquals([], $health->getDetails());
    }

    /**
     * @test
     */
    public function downWithException()
    {
        $health = (new HealthBuilder())->down(new \Exception('Exception Message'))
            ->build();

        $this->assertEquals(Status::DOWN, $health->getStatus());
        $this->assertEquals(['error' => 'Exception Message'], $health->getDetails());
    }

    /**
     * @test
     */
    public function down()
    {
        $health = (new HealthBuilder())->down()
            ->build();

        $this->assertEquals(Status::DOWN, $health->getStatus());
        $this->assertEquals([], $health->getDetails());
    }

    /**
     * @test
     */
    public function outOfService()
    {
        $health = (new HealthBuilder())->outOfService()
            ->build();

        $this->assertEquals(Status::OUT_OF_SERVICE, $health->getStatus());
        $this->assertEquals([], $health->getDetails());
    }

    /**
     * @test
     */
    public function status()
    {
        $health = (new HealthBuilder())->status(Status::UP)
            ->build();

        $this->assertEquals(Status::UP, $health->getStatus());
        $this->assertEquals([], $health->getDetails());
    }
}
