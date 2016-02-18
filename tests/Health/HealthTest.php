<?php
namespace Actuator\Test\Health;

use Actuator\Health\HealthBuilder;
use Actuator\Health\Status;

class HealthTest extends \PHPUnit_Framework_TestCase
{
    public function testStatusDefaultsToUnknown()
    {
        $builder = new HealthBuilder(null, null);
        $this->assertEquals(Status::UNKNOWN, $builder->build()->getStatus());
    }

    public function testCreateWithStatus()
    {
        $builder = new HealthBuilder(new Status(Status::UP), []);
        $health = $builder->build();
        $this->assertEquals(Status::UP, $health->getStatus());
        $this->assertCount(0, (array)$health->getDetails());
    }

    public function testCreateWithDetails()
    {
        $builder = new HealthBuilder(new Status(Status::UP), ['a' => 'b']);
        $health = $builder->build();

        $this->assertEquals(Status::UP, $health->getStatus());
        $this->assertEquals((object)['a' => 'b'], $health->getDetails());
    }

    public function testEquals()
    {
        $health1 = new HealthBuilder(new Status('UP'), ['a' => 'b']);
        $health2 = new HealthBuilder(new Status('UP'), ['a' => 'b']);

        $this->assertEquals($health1, $health2);
    }

    public function testWithException()
    {
        $exception = new \Exception('Test Exception');

        $health = (new HealthBuilder(new Status(Status::UP), ['a' => 'b']))->withException($exception)
            ->build();

        $this->assertEquals((object)['a' => 'b', 'error' => 'Test Exception'], $health->getDetails());
    }

    public function testWithDetails()
    {
        $health = (new HealthBuilder(new Status(Status::UP), ['a' => 'b']))->withDetail('c', 'd')
            ->build();

        $this->assertEquals((object)['a' => 'b', 'c' => 'd'], $health->getDetails());
    }

    public function testWithUnknownDetails()
    {
        $health = (new HealthBuilder())->unknown()
            ->withDetail('a', 'b')
            ->build();
        $this->assertEquals(Status::UNKNOWN, $health->getStatus());
        $this->assertEquals((object)['a' => 'b'], $health->getDetails());
    }

    public function testUnknown()
    {
        $health = (new HealthBuilder())->unknown()
            ->build();

        $this->assertEquals(Status::UNKNOWN, $health->getStatus());
        $this->assertEquals((object)[], $health->getDetails());
    }

    public function testUpWithDetails()
    {
        $health = (new HealthBuilder())->up()
            ->withDetail('a', 'b')
            ->build();

        $this->assertEquals(Status::UP, $health->getStatus());
        $this->assertEquals((object)['a' => 'b'], $health->getDetails());
    }

    public function testUp()
    {
        $health = (new HealthBuilder())
            ->up()
            ->build();

        $this->assertEquals(Status::UP, $health->getStatus());
        $this->assertEquals((object)[], $health->getDetails());
    }

    public function testDownWithException()
    {
        $health = (new HealthBuilder())->down(new \Exception('Exception Message'))
            ->build();

        $this->assertEquals(Status::DOWN, $health->getStatus());
        $this->assertEquals((object)['error' => 'Exception Message'], $health->getDetails());
    }

    public function testDown()
    {
        $health = (new HealthBuilder())->down()
            ->build();

        $this->assertEquals(Status::DOWN, $health->getStatus());
        $this->assertEquals((object)[], $health->getDetails());
    }

    public function testOutOfService()
    {
        $health = (new HealthBuilder())->outOfService()
            ->build();

        $this->assertEquals(Status::OUT_OF_SERVICE, $health->getStatus());
        $this->assertEquals((object)[], $health->getDetails());
    }

    public function testStatus()
    {
        $health = (new HealthBuilder())->status(Status::UP)
            ->build();

        $this->assertEquals(Status::UP, $health->getStatus());
        $this->assertEquals((object)[], $health->getDetails());
    }


}
